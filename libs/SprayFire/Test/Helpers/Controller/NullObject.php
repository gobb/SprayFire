<?php

/**
 * Object returned from Controller factory if there are problems creating the object,
 * such as the class does not exist.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Helpers\Controller;

class NullObject extends \SprayFire\Controller\FireController\Base {

    public function __construct() {
        $this->services = array();
    }

    public function __call($name, $arguments) {

    }

}