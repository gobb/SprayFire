<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Helpers\Controller;

class NullObjectInvalidServices extends \SprayFire\Controller\FireController\Base {

    public function __construct() {
        $this->services = array(
            'SprayFire.NotFoundService'
        );
    }

}