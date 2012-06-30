<?php

/**
 * A logger that will log messages to whatever configuration is set for error_log
 * in `php.ini`
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging\Logifier;

use \SprayFire\Logging\Logger as Logger,
    \SprayFire\CoreObject as CoreObject;


class ErrorLogLogger extends CoreObject implements Logger {

    /**
     * @param string $message
     * @param null $options
     * @return bool|mixed
     */
    public function log($message, $options = null) {
        return \error_log($message);
    }

}