<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Helpers;

/**
 * @package SprayFireTest
 * @subpackage Helpers
 */
class TestBaseFactory extends \SprayFire\Factory\FireFactory\Base {

    public function testGetFinalBlueprint($className, array $options = array()) {
        return $this->getFinalBlueprint($className, $options);
    }

}
