<?php

/**
 * A Null bootstrap to be returned from a Bootstrap Factory
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Bootstrap;

use \SprayFire\Bootstrap\Bootstrapper as Bootstrapper,
    \SprayFire\CoreObject as CoreObject;

class NullObject extends CoreObject implements Bootstrapper {

    public function runBootstrap() {
        return null;
    }

}