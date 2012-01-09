<?php

/**
 * @file
 * @brief A SprayFire.Logger.Logger implementation implementing the NullObject
 * design pattern.
 */

namespace SprayFire\Logging\Logifier;

/**
 * @brief This SprayFire.Logger.Logger will not actually log messages to any medium;
 * logged messages are simply ignored.
 *
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class NullLogger extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Logging\Logger {

    /**
     * @param $message The message to log
     * @return Always returns true
     */
    public function log($message) {
        return true;
    }

}
