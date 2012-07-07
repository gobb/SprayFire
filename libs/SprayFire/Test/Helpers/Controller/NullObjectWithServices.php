<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Helpers\Controller;

class NullObjectWithServices extends \SprayFire\Controller\Base {

    public function __construct() {
        $this->services = array(
            'ServiceOne' => 'SprayFire.Test.Helpers.Controller.NullServiceOne',
            'ServiceTwo' => 'SprayFire.Test.Helpers.Controller.NullServiceTwo'
        );
    }

}

class NullServiceOne {}
class NullServiceTwo {}