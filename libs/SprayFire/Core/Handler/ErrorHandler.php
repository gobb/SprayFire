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

        $normalizeSeverity = function() use ($severity) {
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
        };

        $errorMessage = 'severity:=' . $normalizeSeverity() . ' message:=' . $message;
        $this->log($errorMessage);
        $index = \count($this->trappedErrors);
        $this->trappedErrors[$index] = array();
        $this->trappedErrors[$index]['severity'] = $normalizeSeverity();
        $this->trappedErrors[$index]['message'] = $message;
        $this->trappedErrors[$index]['file'] = $file;
        $this->trappedErrors[$index]['line'] = $line;

        $nonHandledSeverity = array(E_RECOVERABLE_ERROR);
        if (in_array($severity, $nonHandledSeverity)) {
            return false;
        }
    }

    /**
     * @return An array of error info
     */
    public function getTrappedErrors() {
        return $this->trappedErrors;
    }

}