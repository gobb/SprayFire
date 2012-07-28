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

use \SprayFire\Controller\Controller as Controller,
    \SprayFire\CoreObject as CoreObject;

class NullObject extends CoreObject implements Controller {

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

    public function getCleanData() {
        return array();
    }

    public function getDirtyData() {
        return array();
    }

    public function getLayoutPath() {

    }

    public function getResponderName() {

    }

    public function getTemplatePath() {

    }

    public function giveCleanData(array $data) {

    }

    public function giveDirtyData(array $data) {

    }

    public function getRequestedServices() {

    }

    public function giveService($key, $Service) {

    }

}