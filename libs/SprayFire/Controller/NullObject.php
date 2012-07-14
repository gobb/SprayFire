<?php

/**
 * A default controller returned from the controller factory if the original controller
 * requested could not be located, ie a 404 response.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller;

use \SprayFire\Controller\Base as BaseController;

class NullObject extends BaseController {

    /**
     * Here to ensure that this object can invoke any action called upon it so that
     * if this is returned from the controller factory we don't have to change the
     * action used.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments) {
        
    }

}