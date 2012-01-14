<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Helpers;

/**
 * @brief
 */
class TestDelegatorLogger extends \SprayFire\Test\Helpers\DevelopmentLogger {

    protected $options;

    public function __construct($options) {
        $this->options = $options;
    }

    public function getOptions() {
        return $this->options;
    }

}