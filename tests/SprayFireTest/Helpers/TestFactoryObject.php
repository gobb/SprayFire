<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Helpers;

/**
 * @brief
 */
class TestFactoryObject extends \SprayFire\StdLib\CoreObject {

    public $passedParams;

    public function __construct() {
        $this->passedParams = \func_get_args();
    }


}
