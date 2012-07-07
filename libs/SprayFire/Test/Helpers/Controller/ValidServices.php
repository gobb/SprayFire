<?php

/**
 *
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Helpers\Controller;

class ValidServices extends \SprayFire\Controller\Base {

    public function __construct() {
        $this->services = array(
            'ServiceOne' => 'SprayFire.Test.Helpers.Controller.ServiceOne',
            'ServiceTwo' => 'SprayFire.Test.Helpers.Controller.ServiceTwo'
        );
    }

}

class ServiceOne extends \SprayFire\CoreObject {

}

class ServiceTwo extends \SprayFire\CoreObject {

}