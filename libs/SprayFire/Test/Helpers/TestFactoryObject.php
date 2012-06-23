<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Helpers;

/**
 * @brief
 */
class TestFactoryObject extends \SprayFire\CoreObject {

    public $passedParams;

    public function __construct() {
        $this->passedParams = \func_get_args();
    }


}