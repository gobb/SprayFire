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
 */
class NullLogger extends \SprayFire\Core\CoreObject implements \SprayFire\Logger\Logger {

    /**
     * @param $message The message to log
     * @return Always returns true
     */
    public function log($message) {
        return true;
    }

}
