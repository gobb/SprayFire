<?php

/**
 * @file
 * @brief A file holding classes reponsible for logging and keeping track of errors
 * triggered.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011,2012 Charles Sprayberry
 */

namespace SprayFire\Core\Handler;

/**
 * @brief A class that is responsible for trapping errors, logging appropriate
 * messages and provides a means to get the error info for errors triggered in a
 * specific request.
 *
 * @uses SprayFire.Logger.Log
 * @uses SprayFire.Logger.CoreObject
 */
class ErrorHandler extends \SprayFire\Logger\CoreObject {

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
    public function __construct(\SprayFire\Logger\Log $Log, $developmentModeOn = false) {
        parent::__construct($Log);
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

        $normalizedSeverity = $this->normalizeSeverity($severity);
        $this->logMessage($normalizedSeverity, $message);
        $data = \compact('severity', 'message', 'file', 'line');
        if ($this->developmentModeOn) {
            $data['context'] = $context;
        }
        $index = \count($this->trappedErrors);
        $this->trappedErrors[$index] = $data;

        $nonHandledSeverity = array(E_RECOVERABLE_ERROR);
        if (in_array($severity, $nonHandledSeverity)) {
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
        return 'E_UNKOWN_SEVERITY';
    }

    /**
     * @param $normalizedSeverity The textual representation of the severity for the trapped error
     * @param $message The error message that was trapped
     */
    protected function logMessage($normalizedSeverity, $message) {
        $errorMessage = 'severity:=' . $normalizedSeverity . ' message:=' . $message;
        $this->log($errorMessage);
    }

    /**
     * @return An array of error info
     */
    public function getTrappedErrors() {
        return $this->trappedErrors;
    }

}