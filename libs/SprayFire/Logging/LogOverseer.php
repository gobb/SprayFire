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
     * @param $message Information about an emergency situation that occured
     */
    public function logEmergency($message);

    /**
     * @param $message Information about an error that PHP triggered
     */
    public function logError($message);

    /**
     * @param $message Information used for debugging purposes
     */
    public function logDebug($message);

    /**
     * @param $message Information used for informational/data mining purposes
     */
    public function logInfo($message);

}