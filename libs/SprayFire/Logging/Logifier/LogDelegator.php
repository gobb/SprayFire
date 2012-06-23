<?php

/**
 * Holds an implementation of SprayFire.Logging.LogOverseer to manage the destination
 * of specific log messages.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Logging.LogOverseer
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Factory.Factory
 * @uses SprayFire.Util.CoreObject
 */
class LogDelegator extends \SprayFire\CoreObject implements \SprayFire\Logging\LogOverseer {

    /**
     * @property SprayFire.Logging.Logger
     */
    protected $EmergencyLogger;

    /**
     * @property SprayFire.Logging.Logger
     */
    protected $ErrorLogger;

    /**
     * @property SprayFire.Logging.Logger
     */
    protected $DebugLogger;

    /**
     * @property SprayFire.Logging.Logger
     */
    protected $InfoLogger;

    /**
     * @param $LoggerFactory SprayFire.Factory.Factory that produces SprayFire.Logging.Logger
     */
    public function __construct(\SprayFire\Logging\Logger $Emergency, \SprayFire\Logging\Logger $Error, \SprayFire\Logging\Logger $Debug, \SprayFire\Logging\Logger $Info) {
        $this->EmergencyLogger = $Emergency;
        $this->ErrorLogger = $Error;
        $this->DebugLogger = $Debug;
        $this->InfoLogger = $Info;
    }

    /**
     * @param $message string
     * @param $options array Options to pass to \a $DebugLogger->log($message, $options)
     * @return mixed Value from \a $DebugLogger->log()
     */
    public function logDebug($message, array $options = array()) {
        return $this->DebugLogger->log($message, $options);
    }

    /**
     * @param $message string
     * @param $options array Options to pass to \a $EmergencyLogger->log($message, $options)
     * @return mixed Value from \a $EmergencyLogger->log()
     */
    public function logEmergency($message, array $options = array()) {
        return $this->EmergencyLogger->log($message, $options);
    }

    /**
     * @param $message string
     * @param $options array Options to pass to \a $ErrorLogger->log($message, $options)
     * @return mixed Value from \a $ErrorLogger->log()
     */
    public function logError($message, array $options = array()) {
        return $this->ErrorLogger->log($message, $options);
    }

    /**
     * @param $message string
     * @param $options array Options to pass to \a $InfoLogger->log($message, $options)
     * @return mixed Value from \a $InfoLogger->log()
     */
    public function logInfo($message, array $options = array()) {
        return $this->InfoLogger->log($message, $options);
    }

}