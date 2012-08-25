<?php

/**
 * Class responsible for logging and keeping track of errors triggered.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire;

use \SprayFire\Logging\LogOverseer,
    \SprayFire\CoreObject as CoreObject;

class Handler extends CoreObject {

    /**
     * @property SprayFire.Logging.LogOverseer
     */
    protected $Logger;

    protected $developmentMode;

    /**
     * @param SprayFire.Logging.LogOverseer $Log
     * @param boolean $developmentModeOn
     */
    public function __construct(LogOverseer $Log, $developmentModeOn = false) {
        $this->Logger = $Log;
        $this->developmentMode = (boolean) $developmentModeOn;
    }

    /**
     * @param int $severity int representing an error level constant
     * @param string $message string representing an error message
     * @param string $file string representing the file the error occurred in
     * @param int $line int the line that triggered the error in \a $file
     * @param array $context an array of variables available at time of
     * error
     * @return boolean
     */
    public function trapError($severity, $message, $file = null, $line = null, $context = null) {
        if (\error_reporting() === 0) {
            return false;
        }
        $line = 'line:' . (int) $line;
        $message = \implode(';', \compact('message', 'file', 'line'));
        $this->Logger->logError($message);
        $unHandledSeverity = array(E_RECOVERABLE_ERROR);
        if (\in_array($severity, $unHandledSeverity)) {
            return false;
        }

        return true;
    }

    public function trapException(\Exception $Exception) {
        if (!$this->developmentMode) {
            $this->Logger->logEmergency($Exception->getMessage());
        } else {
            \var_dump($Exception);
        }
        \header('HTTP/1.1 500 Internal Server Error');
        \header('Content-Type: text-html; charset=UTF-8');

        echo <<< HTML
<!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Server Error</title>
        </head>
        <body>
            <h1>500 Internal Server Error</h1>
        </body>
    </html>
HTML;

        exit;
    }

    /**
     * Converts a numeric severity into the textual representation of the
     * error level constant representing it; if it the severity is not known or not
     * one expected to be trapped by the handler it will return 'E_UNKNOWN_SEVERITY'
     *
     * @param int $severity
     * @return string
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