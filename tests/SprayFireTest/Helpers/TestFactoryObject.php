<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Helpers;

/**
 * @brief
 */
class TestFactoryObject extends \SprayFire\CoreObject {

    public $passedParams;

    public function __construct() {
        $this->passedParams = \func_get_args();
    }


}
