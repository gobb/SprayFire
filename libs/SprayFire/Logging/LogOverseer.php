<?php

/**
 * Responsible for delegating the appropriate error messages to the appropriate
 * SprayFire.Logging.Logger.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging;

use \SprayFire\Object as Object;

/**
 * Implementations are ultimately acting as a facade to allow the logging
 * of information pertinent to the system to a variety of different places.
 *
 * @see http://www.github.com/cspray/SprayFire/wiki/Logging
 */
interface LogOverseer extends Object {

    /**
     * @param string $message
     * @param array $options
     */
    public function logEmergency($message, array $options = array());

    /**
     * @param string $message
     * @param array $options
     */
    public function logError($message, array $options = array());

    /**
     * @param string $message
     * @param array $options
     */
    public function logDebug($message, array $options = array());

    /**
     * @param string $message
     * @param array $options
     */
    public function logInfo($message, array $options = array());

}