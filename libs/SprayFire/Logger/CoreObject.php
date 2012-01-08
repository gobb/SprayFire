<?php

/**
 * @file
 * @brief Holds an extension of SprayFire.Core.CoreObject to provide a single method
 * to log various messages.
 */

namespace SprayFire\Logger;

/**
 * @brief A CoreObject that allows for extending classes to log messages via a
 * `log()` method.
 *
 * @details
 * By default this method will log a message with the current timestamp at the time
 * of call.  The timestamp format by default is `M-d-Y H:i:s` but you can change
 * the timestamp format by passing the optional second parameter on construction.
 *
 * @uses SprayFire.Logger.Log
 * @uses SprayFire.Core.CoreObject
 */
abstract class CoreObject extends \SprayFire\Core\CoreObject {

    /**
     * @brief A SprayFire.Logger.Log object that is used to store various messages.
     *
     * @property $Log
     */
    protected $Log;

    /**
     * @brief The date format string to use for log messages
     *
     * @property $timestampFormat
     * @see http://php.net/manual/en/function.date.php
     */
    protected $timestampFormat;

    /**
     * @param $Log SprayFire.Logger.Log to be used to store messages
     * @param $timeStampFormat A string representing the format to use for log timestamps
     */
    public function __construct(\SprayFire\Logger\Log $Log, $timeStampFormat = 'M-d-Y H:i:s') {
        $this->Log = $Log;
        $this->timestampFormat = $timeStampFormat;
    }

    /**
     * @param $message A string message to log
     */
    protected function log($message) {
        $timestamp = \date($this->timestampFormat);
        $this->Log->log($timestamp, $message);
    }

}