<?php

/**
 * An interface for implementing objects that are responsible for framework or
 * app bootstrap functions.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Bootstrap;

use \SprayFire\Object as Object;

/**
 * All framework and application bootstraps should implement this interface, an
 * app bootstrap that does not implement this interface is not guaranteed to
 * be ran.
 */
interface Bootstrapper extends Object {

    /**
     * Should perform whatever actions are necessary for the given bootstrap.
     *
     * @return mixed
     */
    public function runBootstrap();

}
