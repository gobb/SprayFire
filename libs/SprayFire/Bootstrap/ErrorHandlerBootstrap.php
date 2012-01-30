<?php

/**
 * @file
 * @brief Holds the bootstrap responsible for creating an error handler object and
 * assigning the appropriate method to the error handler.
 */

namespace SprayFire\Bootstrap;

/**
 * @brief This object is responsible for ensuring the configuration values passed
 * are valid to properly instantiate an object responsible for error handling.
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
    protected $trap;

    /**
     * @brief The configuration object passed should hold at least 2 keys, the
     * 'handler' and the 'method' for the object assigned to trapping errors.
     *
     * @param $LogOverseer SprayFire.Logging.LogOverseer
     * @param $Config SprayFire.Config.Configuration
     */
    public function __construct(\SprayFire\Logging\LogOverseer $LogOverseer, \SprayFire\Config\Configuration $Config) {

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

    }

}