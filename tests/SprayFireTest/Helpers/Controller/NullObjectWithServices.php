<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFireTest\Helpers\Controller;

class NullObjectWithServices extends \SprayFire\Controller\FireController\Base {

    public function __construct() {
        $this->services = array(
            'ServiceOne' => 'SprayFireTest.Helpers.Controller.NullServiceOne',
            'ServiceTwo' => 'SprayFireTest.Helpers.Controller.NullServiceTwo'
        );
    }

}

class NullServiceOne {}
class NullServiceTwo {}
