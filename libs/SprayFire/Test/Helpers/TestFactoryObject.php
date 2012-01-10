<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Helpers;

/**
 * @brief
 */
class TestFactoryObject {

    public $passedParams;

    public function __construct() {
        $this->passedParams = \func_get_args();
    }


}