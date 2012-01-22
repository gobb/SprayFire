<?php

/**
 * @file
 * @brief Holds an implementation of SprayFire.Logging.Logger that uses PHP's
 * `syslog` functionality to store messages.
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class SysLogLogger extends \SprayFire\Util\CoreObject implements \SprayFire\Logging\Logger {

    /**
     * @param $ident A prefix to append to all messages put into syslog
     * @param $loggingOption An int representing the options to use when opening
     *        syslog
     * @param $facility An int representing the facility level to log messages with
     * @see http://www.php.net/manual/en/function.openlog.php
     */
    public function __construct($ident = 'SprayFire', $loggingOption = \LOG_NDELAY, $facility = \LOG_USER) {
        \openlog($ident, $loggingOption, $facility);
    }

    /**
     * @param $message The message to store in syslog
     * @param $options The level at which to store the message
     * @return true on success, false on failure
     * @see http://www.php.net/manual/en/function.syslog.php
     */
    public function log($message, $options = \LOG_ERR) {
        return \syslog($options, $message);
    }

}