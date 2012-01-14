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

    public function __construct(array $options) {
        $this->options = $options;
    }

    public function getOptions() {
        return $options;
    }

}