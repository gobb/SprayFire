<?php

/**
 * Implementation of SprayFire.Logging.Logger intended to be used as a debug tool
 * during development.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging as SFLogging,
    \SprayFire\StdLib as SFStdLib;

/**
 * Be aware that this implementation does not persist the data and any messages
 * logged will be lost after the request is finished processing.
 *
 * @package SprayFire
 * @subpackage Logging.FireLogging
 */
class DevelopmentLogger extends SFStdLib\CoreObject implements SFLogging\Logger {

    /**
     * Stores the messages and options passed to DevelopmentLogger::log
     *
     * @property array
     */
    protected $loggedMessages = [];

    /**
     * Will store the $message and $options passed to be retrieved later by
     * DevelopmentLogger::getLoggedMessages().
     *
     * This method will not actually store the information to a persistent source,
     * the information will be lost after the request is finished.
     *
     * @param string $message
     * @param mixed $options
     * @return boolean
     */
    public function log($message, $options = null) {
        $index = \count($this->loggedMessages);
        $this->loggedMessages[$index] = [];
        $this->loggedMessages[$index]['message'] = $message;
        $this->loggedMessages[$index]['options'] = $options;
        return true;
    }

    /**
     * Returns the messages passed to DevelopmentLogger::log
     *
     * @return array
     */
    public function getLoggedMessages() {
        return $this->loggedMessages;
    }

}
