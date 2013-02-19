<?php

/**
 * Interface for bootstrapping processes during framework or app initialization.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Bootstrap;

use \SprayFire\Object as SFObject;

/**
 * Your application specific bootstraps should implement this interface to ensure
 * that any initialization scripts are ran at start up time.
 *
 * @package SprayFire
 * @subpackage Bootstrap
 */
interface Bootstrapper extends SFObject {

    /**
     * Should perform whatever actions are necessary for the given bootstrap.
     *
     * @return void
     */
    public function runBootstrap();

}
