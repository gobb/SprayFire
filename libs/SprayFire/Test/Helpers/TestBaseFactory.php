<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Helpers;

/**
 * @brief
 */
class TestBaseFactory extends \SprayFire\Factory\BaseFactory {

    public function makeObject($className, array $options = array()) {
        return $this->createObject($className, $options);
    }

    public function testGetFinalBlueprint($className, array $options = array()) {
        return $this->getFinalBlueprint($className, $options);
    }

    public function testReplaceDotsWithSlashes($className) {
        return $this->replaceDotsWithBackSlashes($className);
    }

    public function getObjectName() {
        return 'WhateverYouWant';
    }

}