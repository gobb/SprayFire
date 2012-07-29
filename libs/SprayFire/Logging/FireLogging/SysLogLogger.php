<?php

/**
 * Logger that utilizes PHP's built-in syslog function
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging\Logger as Logger,
    \SprayFire\CoreObject as CoreObject;

class SysLogLogger extends CoreObject implements Logger {

    /**
     * @param string $ident
     * @param int $loggingOption
     * @param int $facility
     */
    public function __construct($ident = 'SprayFire', $loggingOption = \LOG_NDELAY, $facility = \LOG_USER) {
        \openlog($ident, $loggingOption, $facility);
    }

    /**
     * @param string $message
     * @param mixed $options
     * @return mixed
     */
    public function log($message, $options = \LOG_ERR) {
        return \syslog($options, $message);
    }

}