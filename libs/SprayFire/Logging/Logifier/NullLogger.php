<?php

/**
 * SprayFire.Logger.Logger implementation that acts as a Null logger
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging\Logifier;

/**
 * This SprayFire.Logger.Logger will not actually log messages to any medium;
 * logged messages are simply ignored.
 *
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class NullLogger extends \SprayFire\Util\CoreObject implements \SprayFire\Logging\Logger {

    /**
     * @param $message string The message to log
     * @param $options null This parameter is not used in this implementation
     * @return boolean Always returns true
     */
    public function log($message, $options = null) {
        return true;
    }

}
