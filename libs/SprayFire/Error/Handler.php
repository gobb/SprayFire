<?php

/**
 * @file
 * @brief A file holding classes reponsible for logging and keeping track of errors
 * triggered.
 */

namespace SprayFire\Error;

/**
 * @brief A class that is responsible for trapping errors, logging appropriate
 * messages and provides a means to get the error info for errors triggered in a
 * specific request.
 *
 * @uses SprayFire.Logging.LogOverseer
 * @uses SprayFire.Core.Util.CoreObject
 */
class Handler extends \SprayFire\Util\CoreObject {

    /**
     * @brief A SprayFire.Logging.LogOverseer used to log messages.
     *
     * @property $Logger
     */
    protected $Logger;

    /**
     * @param $Log \SprayFire\Logger\Log The log to use for this error handler
     * @param $developmentModeOn True or false on whether or not the environment is in development mode
     */
    public function __construct(\SprayFire\Logging\LogOverseer $Log, $developmentModeOn = false) {
        $this->Logger = $Log;
        $this->developmentModeOn = (boolean) $developmentModeOn;
    }

    /**
     * @brief Used as the error handling callback.
     *
     * @param $severity int representing an error level constant
     * @param $message string representing an error message
     * @param $file string representing the file the error occurred in
     * @param $line int the line that triggered the error in \a $file
     * @param type $context an array of variables available at time of error
     * @return false if non-user implemented error handling should be invoked
     */
    public function trap($severity, $message, $file = null, $line = null, $context = null) {
        if (\error_reporting() === 0) {
            return false;
        }
        $line = 'line:' . (int) $line;
        $message = \implode(';', \compact('message', 'file', 'line'));
        $this->Logger->logError($message);
        $intSeverity = $severity;
        $severity = $this->normalizeSeverity($severity);
        $unHandledSeverity = array(E_RECOVERABLE_ERROR);
        if (\in_array($intSeverity, $unHandledSeverity)) {
            return false;
        }
    }

    /**
     * @brief Converts a numeric severity into the textual representation of the
     * error level constant represnting it; if it the severity is not known or not
     * one expected to be trapped by the handler it will return 'E_UNKNOWN_SEVERITY'
     *
     * @param $severity An integer
     * @return string representing the name of the constant equal to \a $severity
     */
    protected function normalizeSeverity($severity) {
        $severityMap = array(
            E_WARNING => 'E_WARNING',
            E_NOTICE => 'E_NOTICE',
            E_STRICT => 'E_STRICT',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED'
        );
        if (\array_key_exists($severity, $severityMap)) {
            return $severityMap[$severity];
        }
        return 'E_UNKNOWN_SEVERITY';
    }

}