<?php

/**
 * Class responsible for logging and keeping track of errors triggered.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire;

use \SprayFire\Logging as SFLogging,
    \SprayFire\CoreObject as SFCoreObject,
    \Exception as Exception;

/**
 * A universal error and exception handler designed to log various error messages and
 * display the appropriate 500 response when an uncaught exception occurs.
 *
 * @package SprayFire
 */
class Handler extends SFCoreObject {

    /**
     * Provides facilities to log error messages that are trapped.
     *
     * @property SprayFire.Logging.LogOverseer
     */
    protected $Logger;

    /**
     * Used to determine whether we should log uncaught exception messages
     * or simply var_dump the exception out to the user.
     *
     * @property boolean
     */
    protected $developmentMode;

    /**
     * @param \SprayFire\Logging\LogOverseer $Log
     * @param boolean $developmentModeOn
     */
    public function __construct(SFLogging\LogOverseer $Log, $developmentModeOn = false) {
        $this->Logger = $Log;
        $this->developmentMode = (boolean) $developmentModeOn;
    }

    /**
     * @param int $severity int representing an error level constant
     * @param string $message string representing an error message
     * @param string $file string representing the file the error occurred in
     * @param int $line int the line that triggered the error in $file
     * @param array $context an array of variables available at time of error
     * @return boolean
     */
    public function trapError($severity, $message, $file = null, $line = null, $context = null) {
        if (\error_reporting() === 0) {
            return false;
        }
        $line = 'line:' . (int) $line;
        $message = \implode(';', \compact('message', 'file', 'line'));
        $this->Logger->logError($message);
        // this is here to ensure that compile errors passing the wrong object type occur as appropriate
        $unHandledSeverity = array(E_RECOVERABLE_ERROR);
        if (\in_array($severity, $unHandledSeverity)) {
            return false;
        }

        return true;
    }

    /**
     * Traps uncaught exceptions and spits out an appropriate 500 response.
     *
     * All script execution is halted after this function is invoked.
     *
     * @param \Exception $Exception
     *
     * @codeCoverageIgnore
     *
     * @todo
     * We need to take a look at some way we can allow custom 500 responses to
     * be sent.
     */
    public function trapException(Exception $Exception) {
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

}
