<?php

/**
 * An implementation of SprayFire.Logging.Logger that uses PHP's `syslog` functionality
 * to store messages.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class SysLogLogger extends \SprayFire\CoreObject implements \SprayFire\Logging\Logger {

    /**
     * @param $ident string A prefix to append to all messages put into syslog
     * @param $loggingOption int The options to use when opening syslog
     * @param $facility int The facility level to log messages with
     * @see http://www.php.net/manual/en/function.openlog.php
     */
    public function __construct($ident = 'SprayFire', $loggingOption = \LOG_NDELAY, $facility = \LOG_USER) {
        \openlog($ident, $loggingOption, $facility);
    }

    /**
     * @param $message string The message to store in syslog
     * @param $options int The level at which to store the message
     * @return boolean true on success, false on failure
     * @see http://www.php.net/manual/en/function.syslog.php
     */
    public function log($message, $options = \LOG_ERR) {
        return \syslog($options, $message);
    }

}