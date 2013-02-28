<?php

/**
 * Implementation of SprayFire.Logging.Logger
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging as SFLogging,
    \SprayFire\StdLib as SFStdLib;

/**
 * This implementation will log messages to the PHP provided error_log function.
 *
 * @package SprayFire
 * @subpackage Logging.FireLogging
 *
 * @see http://us.php.net/manual/en/function.error-log.php
 */
class ErrorLogLogger extends SFStdLib\CoreObject implements SFLogging\Logger {

    /**
     * @param string $message
     * @param null $options
     * @return boolean
     */
    public function log($message, $options = null) {
        return \error_log($message);
    }

}
