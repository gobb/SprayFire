<?php

/**
 * @file
 * @brief A SprayFire.Logger.Logger implementation implementing the NullObject
 * design pattern.
 */

namespace SprayFire\Logger;

/**
 * @brief This SprayFire.Logger.Logger will not actually log messages to any medium;
 * logged messages are simply ignored.
 *
 * @uses SprayFire.Logger.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class NullLogger extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Logger\Logger {

    /**
     * @param $message The message to log
     * @return Always returns true
     */
    public function log($message) {
        return true;
    }

}
