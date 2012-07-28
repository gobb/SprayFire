<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Helpers;

/**
 * @brief
 */
class TestBaseFactory extends \SprayFire\Factory\FireFactory\Base {

    public function testGetFinalBlueprint($className, array $options = array()) {
        return $this->getFinalBlueprint($className, $options);
    }

}