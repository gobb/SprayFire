<?php

/**
 * @file
 * @brief A file holding classes reponsible for logging and keeping track of errors
 * triggered.
 */

namespace SprayFire\Core\Handler;

/**
 * @brief A class that is responsible for trapping errors, logging appropriate
 * messages and provides a means to get the error info for errors triggered in a
 * specific request.
 *
 * @uses SprayFire.Logger.Log
 * @uses SprayFire.Core.Util.CoreObject
 */
class ErrorHandler extends \SprayFire\Core\Util\CoreObject {

    protected $Logger;

    /**
     * @brief An array holding the \a $severity , \a $message , \a $file and \a $line
     * for each triggered error.
     *
     * @property $trappedErrors
     */
    protected $trappedErrors = array();

    /**
     * @brief True/false on whether or not the environment is in development mode.
     *
     * @property $developmentModeOn
     */
    protected $developmentModeOn;

    /**
     * @param $Log \SprayFire\Logger\Log The log to use for this error handler
     * @param $developmentModeOn True or false on whether or not the environment is in development mode
     */
    public function __construct(\SprayFire\Logger\Logger $Log, $developmentModeOn = false) {
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

        $intSeverity = $severity;
        $severity = $this->normalizeSeverity($severity);

        $data = \compact('severity', 'message', 'file', 'line');
        if ($this->developmentModeOn) {
            $data['context'] = $context;
            // We are using this method to log the info because we want more information
            // about errors if we're in development mode.
            $this->Logger->log($data);
        } else {
            $this->Logger->log($message);
        }

        $index = \count($this->trappedErrors);
        $this->trappedErrors[$index] = $data;

        $nonHandledSeverity = array(E_RECOVERABLE_ERROR);
        if (\in_array($intSeverity, $nonHandledSeverity)) {
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

    /**
     * @return An array of error info
     */
    public function getTrappedErrors() {
        return $this->trappedErrors;
    }

}