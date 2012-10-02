<?php

/**
 * Implementation of SprayFire.Logging.LogOverseer that uses other FireLogging
 * implementations to provide functionality.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging as SFLogging,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * Provides the ability to log information at a variety of levels and sources.
 *
 * @package SprayFire
 * @subpackage Logging.FireLogging
 *
 * @todo
 * We need to create a test to test getting a logger and ensuring that the
 * appropriate loggers are used for the appropriate calls.
 */
class LogOverseer extends SFCoreObject implements SFLogging\LogOverseer {

    /**
     * This SprayFire.Logging.Logger can be retrieved from LogOverseer::getLogger()
     * by passing SprayFire.Logging.LogOverseer::EMERGENCY_LOGGER.
     *
     * @property SprayFire.Logging.Logger
     */
    protected $EmergencyLogger;

    /**
     * This SprayFire.Logging.Logger can be retrieved from LogOverseer::getLogger()
     * by passing SprayFire.Logging.LogOverseer::ERROR_LOGGER.
     *
     * @property SprayFire.Logging.Logger
     */
    protected $ErrorLogger;

    /**
     * This SprayFire.Logging.Logger can be retrieved from LogOverseer::getLogger()
     * by passing SprayFire.Logging.LogOverseer::DEBUG_LOGGER
     *
     * @property SprayFire.Logging.Logger
     */
    protected $DebugLogger;

    /**
     * This SprayFire.Logging.Logger can be retrieved from LogOverseer::getLogger()
     * by passing SprayFire.Logging.LogOverseer::INFO_LOGGER
     *
     * @property SprayFire.Logging.Logger
     */
    protected $InfoLogger;

    /**
     * @param SprayFire.Logging.Logger $Emergency
     * @param SprayFire.Logging.Logger $Error
     * @param SprayFire.Logging.Logger $Debug
     * @param SprayFire.Logging.Logger $Info
     */
    public function __construct(
        SFLogging\Logger $Emergency,
        SFLogging\Logger $Error,
        SFLogging\Logger $Debug,
        SFLogging\Logger $Info
    ) {
        $this->EmergencyLogger = $Emergency;
        $this->ErrorLogger = $Error;
        $this->DebugLogger = $Debug;
        $this->InfoLogger = $Info;
    }

    /**
     * @param string $message
     * @param array $options
     * @return mixed
     */
    public function logDebug($message, array $options = array()) {
        return $this->DebugLogger->log($message, $options);
    }

    /**
     * @param string $message
     * @param array $options
     * @return mixed
     */
    public function logEmergency($message, array $options = array()) {
        return $this->EmergencyLogger->log($message, $options);
    }

    /**
     * @param string $message
     * @param array $options
     * @return mixed
     */
    public function logError($message, array $options = array()) {
        return $this->ErrorLogger->log($message, $options);
    }

    /**
     * @param string $message
     * @param array $options
     * @return mixed
     */
    public function logInfo($message, array $options = array()) {
        return $this->InfoLogger->log($message, $options);
    }

    /**
     * Will return a SprayFire.Logging.Logger if the passed $loggerType is valid
     * or null if an invalid type is passed to $loggerType.
     *
     * @param string $loggerType
     * @return mixed
     */
    public function getLogger($loggerType) {
        $loggerType = \strtolower($loggerType);
        switch($loggerType) {
            case SFLogging\LogOverseer::EMERGENCY_LOGGER:
                return $this->EmergencyLogger;
                break;
            case SFLogging\LogOverseer::ERROR_LOGGER:
                return $this->ErrorLogger;
                break;
            case SFLogging\LogOverseer::INFO_LOGGER:
                return $this->InfoLogger;
                break;
            case SFLogging\LogOverseer::DEBUG_LOGGER:
                return $this->DebugLogger;
                break;
            default:
                return null;

        }
    }

}