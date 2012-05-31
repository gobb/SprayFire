<?php

/**
 * @file
 * @brief Holds a class to set the configuration values for php.ini
 */

namespace SprayFire\Bootstrap\Strapifier;

/**
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Util.CoreObject
 */
class IniSetting extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief An associative array holding the settings and values that should be
     * used in the ini_set() call
     *
     * @var $config
     */
    protected $config;

    /**
     * @param $config Associative array, setting => value
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * @brief Will set the global ini configuration settings and either the development
     * or production ini configuration.
     *
     * @return void
     */
    public function runBootstrap() {
        foreach ($this->config as $setting => $value) {
            \ini_set($setting, $value);
        }
    }

}