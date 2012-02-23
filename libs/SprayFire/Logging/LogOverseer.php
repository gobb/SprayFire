<?php

/**
 * @file
 * @brief An interface that is responsible for delegating the appropriate error
 * messages to the appropriate SprayFire.Logging.Logger.
 */

namespace SprayFire\Logging;

/**
 * @brief Implementations are ultimately acting as a facade to allow the logging
 * of information pertinent to the system to a variety of different places.
 *
 * @see http://www.github.com/cspray/SprayFire/wiki/Logging
 */
interface LogOverseer {

    /**
     * @param $message string Information about an emergency situation that occured
     * @param $options array An array of options to pass to the emergency logger
     */
    public function logEmergency($message, array $options = array());

    /**
     * @param $message string Information about an error that PHP triggered
     * @param $options array An array of options to pass to the error logger
     */
    public function logError($message, array $options = array());

    /**
     * @param $message string Information used for debugging purposes
     * @param $options array An array of options to pass to the debug logger
     */
    public function logDebug($message, array $options = array());

    /**
     * @param $message string Information used for informational/data mining purposes
     * @param $options array An array of options to pass to the info logger
     */
    public function logInfo($message, array $options = array());

}