<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Logger;

/**
 * @brief
 */
class NullLogger extends \SprayFire\Core\CoreObject implements \SprayFire\Logger\Logger {

    public function log($message) {
        return true;
    }

}