<?php

/**
 * Implementation of SprayFire.Bootstrap.Bootstrapper that performs no operations
 *
 * @author   Charles Sprayberry
 * @license  Subject to the terms of the LICENSE file in the project root
 * @version  0.1
 * @since    0.1
 */

namespace SprayFire\Bootstrap;

use \SprayFire\Bootstrap\Bootstrapper as Bootstrapper,
    \SprayFire\CoreObject as CoreObject;

/**
 * The primary use case for this object would be as a possible return value from
 * a factory that creates SprayFire.Bootstrap.Bootstrapper objects.
 */
class NullObject extends CoreObject implements Bootstrapper {

    /**
     * Performs no operation and returns null.
     *
     * @return null
     */
    public function runBootstrap() {
        return null;
    }

}