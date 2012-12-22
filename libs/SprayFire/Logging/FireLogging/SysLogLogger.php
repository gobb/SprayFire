<?php

/**
 * Implementation of SprayFire.Logging.Logger that logs information using syslog.
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
 * There are a variety of options that can be passed to the constructor and
 * log() methods, these options are passed as PHP syslog constants.
 *
 * The values for constructor options can be found at
 * http://us.php.net/manual/en/function.openlog.php
 *
 * The values for log() options can be found at
 * http://us.php.net/manual/en/function.syslog.php
 *
 * @package SprayFire
 * @subpackage Logging.FireLogging
 */
class SysLogLogger extends SFCoreObject implements SFLogging\Logger {

    /**
     * Will open the syslog with the appropriate options, preparing it for use.
     *
     * @param string $ident
     * @param int $loggingOption
     * @param int $facility
     */
    public function __construct($ident = 'SprayFire', $loggingOption = \LOG_NDELAY, $facility = \LOG_USER) {
        \openlog($ident, $loggingOption, $facility);
    }

    /**
     * The options passed to this array should be one of the syslog constants.
     *
     * @param string $message
     * @param int|mixed|null $options
     * @return boolean
     */
    public function log($message, $options = \LOG_ERR) {
        return \syslog($options, $message);
    }

}
