<?php

/**
 * A class to set php.ini settings based on an array passed in constructor
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Bootstrap\Strapifier;

/**
 * The array should be an associative array that stores the key as the ini setting
 * and the value of that key as the value it should be set to.
 *
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Util.CoreObject
 */
class IniSetting extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * Associative array holding the settings and values that should be used in
     * the ini_set() call
     *
     * @property array
     */
    protected $config;

    /**
     * @param $config Associative array, setting => value
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Will set the global ini configuration settings and either the development
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