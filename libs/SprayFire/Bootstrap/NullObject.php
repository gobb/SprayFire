<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Bootstrap;

/**
 * @brief
 */
class NullObject extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    public function runBootstrap() {
        return null;
    }

}