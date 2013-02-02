<?php

/**
 *
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFireTest\Helpers\Controller;

class ValidServices extends \SprayFire\Controller\FireController\Base {

    public function __construct() {
        $this->services = array(
            'ServiceOne' => 'SprayFireTest.Helpers.Controller.ServiceOne',
            'ServiceTwo' => 'SprayFireTest.Helpers.Controller.ServiceTwo'
        );
    }

}

class ServiceOne extends \SprayFire\CoreObject {

}

class ServiceTwo extends \SprayFire\CoreObject {

}
