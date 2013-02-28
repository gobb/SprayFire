<?php

/**
 * Implementation of SprayFire.Bootstrap.Bootstrapper that performs no operations
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Bootstrap;

use \SprayFire\Bootstrap as SFBootstrap,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * Provided so that SprayFire.Factory.Factory objects creating objects implementing
 * SprayFire.Bootstrap.Bootstrapper can return an appropriate null object if so
 * configured.
 *
 * @package SprayFire
 * @subpackage Bootstrap
 */
class NullObject extends SFCoreObject implements SFBootstrap\Bootstrapper {

    /**
     * Performs no operation and returns null.
     *
     * @return null
     */
    public function runBootstrap() {
        return null;
    }

}
