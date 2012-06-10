<?php

/**
 * A Null bootstrap to be returned from a Bootstrap Factory
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Bootstrap;

class NullObject extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    public function runBootstrap() {
        return null;
    }

}