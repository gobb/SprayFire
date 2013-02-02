<?php

/**
 * Interface to handle logging information to a variety of levels and resources.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Logging;

use \SprayFire\Object as SFObject;

/**
 * Implementations should use the SprayFire.Logging.Logger interface in some
 * capacity to provide the appropriate services for logging to at least 4 different
 * levels: Emergency, Error, Debug and Info.
 *
 * @package SprayFire
 * @subpackage Logging
 */
interface LogOverseer extends SFObject {

    /**
     * An internal ID of the Emergency logger, primary use case is an argument
     * to SprayFire.Logging.LogOverseer::getLogger
     */
    const EMERGENCY_LOGGER = 'emerg';

    /**
     * An internal ID of the Error logger, primary use case is an argument to
     * SprayFire.Logging.LogOverseer::getLogger
     */
    const ERROR_LOGGER = 'err';

    /**
     * An internal ID of the Debug logger, primary use case is an argument to
     * SprayFire.Logging.LogOverseer::getLogger
     */
    const DEBUG_LOGGER = 'deb';

    /**
     * An internal ID of the Info logger, primary use case is an argument to
     * SprayFire.Logging.LogOverseer::getLogger
     */
    const INFO_LOGGER = 'inf';

    /**
     * @param string $message
     * @param array $options
     */
    public function logEmergency($message, array $options = []);

    /**
     * @param string $message
     * @param array $options
     */
    public function logError($message, array $options = []);

    /**
     * @param string $message
     * @param array $options
     */
    public function logDebug($message, array $options = []);

    /**
     * @param string $message
     * @param array $options
     */
    public function logInfo($message, array $options = []);

    /**
     * For compatibility purposes you should implement this function to accept a
     * $loggerType using the constants provided by this interface.
     *
     * @param string $loggerType
     * @return SprayFire.Logging.Logger
     */
    public function getLogger($loggerType);

}
