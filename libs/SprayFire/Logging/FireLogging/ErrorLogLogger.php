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
    \SprayFire\CoreObject as SFCoreObject;

/**
 * This implementation will log messages to the PHP provided error_log function.
 *
 * @package SprayFire
 * @subpackage Logging.FireLogging
 *
 * @see http://us.php.net/manual/en/function.error-log.php
 */
class ErrorLogLogger extends CoreObject implements Logger {

    /**
     * @param string $message
     * @param null $options
     * @return boolean
     *
     * @todo
     * We need to take a look at having this method accept options as additional
     * parameters are availble to the underlying implementation that should be
     * exposed to calling code.  Perhaps an array of additional parameters that
     * can be used with a call_user_func_array
     */
    public function log($message, $options = null) {
        return \error_log($message);
    }

}