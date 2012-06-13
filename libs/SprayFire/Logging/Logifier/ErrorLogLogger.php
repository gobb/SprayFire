<?php

/**
 * A logger that will log messages to whatever configuration is set for error_log
 * in `php.ini`
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Util.CoreObject
 */
class ErrorLogLogger extends \SprayFire\Util\CoreObject implements \SprayFire\Logging\Logger {

    /**
     * @param $message string The information to log in error_log
     * @param $options array This parameter is not used in this implementation
     * @return boolean true on success, false on failure
     * @see http://php.net/manual/en/function.error-log.php
     */
    public function log($message, $options = null) {
        return \error_log($message);
    }

}