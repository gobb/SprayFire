<?php

/**
 * @file
 * @brief Holds the bootstrap responsible for creating an error handler object and
 * assigning the appropriate method to the error handler.
 */

namespace SprayFire\Bootstrap;

/**
 * @brief This object is responsible for ensuring the configuration values passed
 * are valid to properly instantiate an object responsible for error handling, instantiating
 * that object and setting the method associated with that handler as the callback
 * function for set_error_handler
 *
 * @details
 * There are 2 keys that we are looking for in the configuration object passed.
 *
 * 'handler' = PHP or Java-style namespaced class holding error callback
 * 'method' = the exact, case-sensitive name of the method to be used as callback;
 *            should be a method in handler
 *
 * If these two configuration values are properly set then the bootstrap will create,
 * set and return the appropriate error handler object.
 *
 * It is expected that the handler object set will be expecting a SprayFire.Logging.LogOverseer
 * to be injected into the first parameter and the error handler to not need any
 * other parameters injected.
 *
 * @uses ReflectionClass
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Util.UtilObject
 * @uses SprayFire.Exception.BootstrapFailedException
 *
 */
class ErrorHandlerBootstrap extends \SprayFire\Util\UtilObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief This object is both used to log error messages that may occur attempting
     * to create the appropriate error handler but is also used as the first argument
     * passed to the ErrorHandler constructor.
     *
     * @var $LogOverseer
     */
    protected $LogOverseer;

    /**
     * @brief The PHP-style class name for the object holding the error handler
     * callback.
     *
     * @var $handler
     */
    protected $handler;

    /**
     * @brief The case-sensitive name of the method to be used as the error handler
     * callback.
     *
     * @var $trap
     */
    protected $method;

    /**
     * @brief Holds a ReflectionClass of \a $this->handler to be used to determine
     * if the
     *
     * @var $ReflectedHandler
     */
    protected $ReflectedHandler;

    /**
     *
     * @var $callbackSet
     */
    protected $callbackSet;

    /**
     * @brief The configuration object passed should hold at least 2 keys, the
     * 'handler' and the 'method' for the object assigned to trapping errors.
     *
     * @param $LogOverseer SprayFire.Logging.LogOverseer
     * @param $Config SprayFire.Config.Configuration
     */
    public function __construct(\SprayFire\Logging\LogOverseer $LogOverseer, \SprayFire\Config\Configuration $Config) {
        $this->LogOverseer = $LogOverseer;
        $this->handler = $this->convertJavaClassToPhpClass($Config->handler);
        $this->method = $Config->method;
    }

    /**
     * @brief Will validate that the error handler object and method set in the
     * passed configuration, instantiate them if they are and then set the error
     * handler to the object and method defined.
     *
     * @details
     * If the configuration is not valid, the class doesn't exist or the method
     * is not associated with that class then a SprayFire.Exception.BootstrapFailedException
     * will be thrown.
     *
     * @return The object as defined by \a $this->handler
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    public function runBootstrap() {
        $this->throwExceptionIfHandlerOrMethodAreEmpty();
        $this->createReflectedHandler();
        $this->throwExceptionIfHandlerCouldNotBeLoaded();
        $this->throwExceptionIfHandlerDoesNotHaveMethod();
        $Handler = $this->getHandlerInstance();
        $this->setErrorHandlerCallback($Handler);
        $this->throwExceptionIfCallbackNotSet();
        return $Handler;
    }

    /**
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    protected function throwExceptionIfHandlerOrMethodAreEmpty() {
        if (empty($this->handler) || empty($this->method)) {
            throw new \SprayFire\Exception\BootstrapFailedException('The handler or method was not properly set in the configuration.');
        }
    }

    /**
     * @return ReflectionClass of \a $this->handler
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    protected function createReflectedHandler() {
        try {
            $this->ReflectedHandler = new \ReflectionClass($this->handler);
        } catch(\ReflectionException $ReflectExc) {
            // we don't need to do anything with this, we take care of it later
            // we're just setting this to null as extra insurance
            $this->ReflectedHandler = null;
        }
    }

    /**
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    protected function throwExceptionIfHandlerCouldNotBeLoaded() {
        if (!$this->ReflectedHandler) {
            throw new \SprayFire\Exception\BootstrapFailedException('The class, ' . $this->handler . ', could not be loaded.');
        }
    }

    /**
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    protected function throwExceptionIfHandlerDoesNotHaveMethod() {
        if (!$this->ReflectedHandler->hasMethod($this->method)) {
            throw new \SprayFire\Exception\BootstrapFailedException('The method, ' . $this->method . ', does not exist in, ' . $this->handler . '.');
        }
    }

    /**
     * @return An instance of \a $this->ReflectedHandler
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    protected function getHandlerInstance() {
        try {
            return $this->ReflectedHandler->newInstance($this->LogOverseer);
        } catch(\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\BootstrapFailedException('There was an error instantiating the handler.', null, $ReflectExc);
        }
    }

    /**
     * @brief Note that we are expecting an error handler to already be set in this
     * code, if an error handler has not been set then this method will erroneously
     * throw an exception.
     *
     * @details
     * Ultimately we're bound to this hacky reliance on knowing that an error handler
     * has already been set because the set_error_handler method returns null on
     * a failed callback AND if a callback is being set for the first time.  This
     * is an internal "flaw" with PHP and has no reasonable workaround beside setting
     * the error handler twice, once with a simple closure that does nothing, and
     * then with the real handler to make sure it gets called.  We decided not to
     * go with this approach because it would add too much complexity and adds an
     * unncessary function call to every request.
     *
     * @param $Handler An instance of \a $this->ReflectedHandler
     * @see http://www.php.net/manual/en/function.set-error-handler.php
     */
    protected function setErrorHandlerCallback($Handler) {
        $this->callbackSet = false;
        $prevHandler = \set_error_handler(array($Handler, $this->method));
        if (!\is_null($prevHandler)) {
            $this->callbackSet = true;
        }
    }

    /**
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    protected function throwExceptionIfCallbackNotSet() {
        if (!$this->callbackSet) {
            throw new \SprayFire\Exception\BootstrapFailedException('The error handler was not set or may have been the first error handler set; please check your error handler configuration or the documentation for ' . __CLASS__);
        }
    }

}