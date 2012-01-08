<?php

/**
 * @file
 * @brief Holds a class that implements error logging to an OS or system logger.
 */

namespace SprayFire\Logger;

/**
 * @brief A SprayFire.Logger.Log implementation that will attempt to log a message
 * to syslog and as a fallback will log the message to whatever option is set in
 * the error_log php.ini directive.
 *
 * @uses SprayFire.Logger.Log
 * @uses SprayFire.Core.CoreObject
 */
class SystemLogger extends \SprayFire\Core\CoreObject implements \SprayFire\Logger\Log {

    /**
     * @brief A boolean flag to tell if the syslog was able to be opened or not.
     *
     * @property $syslogOpened
     */
    protected $syslogOpened = false;

    /**
     * @param $syslogIdent
     */
    public function __construct($syslogIdent = 'SprayFire') {
        $this->syslogOpened = \openlog($syslogIdent, \LOG_NDELAY, \LOG_USER);
    }

    /**
     * @param $syslogSeverity The timestamp for the \a $message being logged
     * @param $message The message to be logged
     * @return true if message logged, false if not
     */
    public function log($syslogSeverity, $message) {
        $loggedtoSyslog = $this->logToSyslog($syslogSeverity, $message);
        if (!$loggedtoSyslog) {
            return $this->logToErrorLog($message);
        }
        return true;
    }

    /**
     * @param $syslogSeverity The severity of the syslog message to store
     * @param $message The message to store in syslog
     * @return true if the message was logged into syslog, false if it wasn't
     * @see http://www.php.net/syslog
     */
    protected function logToSyslog($syslogSeverity, $message) {
        if ($this->syslogOpened) {
            return \syslog($syslogSeverity, $message);
        }
        return false;
    }

    /**
     * @param $message The message to log with error_log
     * @return true if logged, false if not
     * @see http://php.net/manual/en/function.error-log.php
     */
    protected function logToErrorLog($message) {
        $message = \date('M-d-Y H:i:s') . ' := ' . $message;
        return \error_log($message);
    }

    /**
     * @brief Ensures that the syslog is properly closed if it was opened.
     */
    public function __destruct() {
        if ($this->syslogOpened) {
            \closelog();
        }
    }

}