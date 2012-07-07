<?php

/**
 * Holds an implementation of SprayFire.Logging.LogOverseer to manage the destination
 * of specific log messages.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging\Logifier;

use \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\Logging\Logger as Logger,
    \SprayFire\CoreObject as CoreObject;

class LogDelegator extends CoreObject implements LogOverseer {

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
     * @param SprayFire.Logging.Logger $Emergency
     * @param SprayFire.Logging.Logger $Error
     * @param SprayFire.Logging.Logger $Debug
     * @param SprayFire.Logging.Logger $Info
     */
    public function __construct(Logger $Emergency, Logger $Error, Logger $Debug, Logger $Info) {
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

}