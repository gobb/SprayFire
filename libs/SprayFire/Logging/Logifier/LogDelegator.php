<?php

/**
 * @file
 * @brief Holds the primary implementation of SprayFire.Logging.LogOverseer.
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Logging.LogOverseer
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Factory.Factory
 * @uses SprayFire.Core.Util.CoreObject
 */
class LogDelegator extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Logging\LogOverseer {

    /**
     * @property $Factory A SprayFire.Factory.Factory object to make SprayFire.Logging.Logger
     *           objects
     */
    protected $Factory;

    /**
     * @property $EmergencyLogger A SprayFire.Logging.Logger object to log emergency
     *           messages to.
     */
    protected $EmergencyLogger;

    /**
     * @property $ErrorLogger A SprayFire.Logging.Logger to log error messages to
     */
    protected $ErrorLogger;

    /**
     * @property $DebugLogger A SprayFire.Logging.Logger to log debug messages to
     */
    protected $DebugLogger;

    /**
     * @property $InfoLogger A SprayFire.Logging.Logger to log info messages to
     */
    protected $InfoLogger;

    /**
     * @param $LoggerFactory Should be a SprayFire.Factory.Factory that will produce
     *        SprayFire.Logging.Logger objects.
     */
    public function __construct(\SprayFire\Factory\Factory $LoggerFactory) {
        $this->Factory = $LoggerFactory;
    }

    /**
     * @param $message string
     * @param $options Array of options to pass to \a $DebugLogger->log($message, $options)
     * @return mixed; return value from \a $DebugLogger->log()
     */
    public function logDebug($message, array $options = array()) {
        return $this->DebugLogger->log($message, $options);
    }

    /**
     * @param $message string
     * @param $options Array of options to pass to \a $EmergencyLogger->log($message, $options)
     * @return mixed; return value from \a $EmergencyLogger->log()
     */
    public function logEmergency($message, array $options = array()) {
        return $this->EmergencyLogger->log($message, $options);
    }

    /**
     * @param $message string
     * @param $options Array of options to pass to \a $ErrorLogger->log($message, $options)
     * @return mixed; return value from \a $ErrorLogger->log()
     */
    public function logError($message, array $options = array()) {
        return $this->ErrorLogger->log($message, $options);
    }

    /**
     * @param $message string
     * @param $options Array of options to pass to \a $InfoLogger->log($message, $options)
     * @return mixed; return value from \a $InfoLogger->log()
     */
    public function logInfo($message, array $options = array()) {
        return $this->InfoLogger->log($message, $options);
    }

    /**
     * @param $loggerName The name of the object to produce by \a $Factory
     * @param $loggerBlueprint The blueprint for this particular object
     */
    public function setEmergencyLogger($loggerName, array $loggerBlueprint = array()) {
        $this->EmergencyLogger = $this->Factory->makeObject($loggerName, $loggerBlueprint);
    }

    /**
     * @param $loggerName The name of the object to produce by \a $Factory
     * @param $loggerBlueprint The blueprint for this particular object
     */
    public function setErrorLogger($loggerName, array $loggerBlueprint = array()) {
        $this->ErrorLogger = $this->Factory->makeObject($loggerName, $loggerBlueprint);
    }

    /**
     * @param $loggerName The name of the object to produce by \a $Factory
     * @param $loggerBlueprint The blueprint for this particular object
     */
    public function setDebugLogger($loggerName, array $loggerBlueprint = array()) {
        $this->DebugLogger = $this->Factory->makeObject($loggerName, $loggerBlueprint);
    }

    /**
     * @param $loggerName The name of the object to produce by \a $Factory
     * @param $loggerBlueprint The blueprint for this particular object
     */
    public function setInfoLogger($loggerName, array $loggerBlueprint = array()) {
        $this->InfoLogger = $this->Factory->makeObject($loggerName, $loggerBlueprint);
    }

}