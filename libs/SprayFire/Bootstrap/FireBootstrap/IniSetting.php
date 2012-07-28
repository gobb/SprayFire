<?php

/**
 * Class to set php.ini settings based on an array passed in constructor
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Bootstrap\FireBootstrap;

use \SprayFire\Bootstrap\Bootstrapper as Bootstrapper,
    \SprayFire\CoreObject as CoreObject;

class IniSetting extends CoreObject implements Bootstrapper {

    /**
     * Associative array holding the settings and values that should be used in
     * the ini_set() call, setting => value
     *
     * @property array
     */
    protected $config;

    /**
     * The array should be associative with ini_setting => value
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Will set the global ini configuration settings and either the development
     * or production ini configuration.
     */
    public function runBootstrap() {
        foreach ($this->config as $setting => $value) {
            \ini_set($setting, $value);
        }
    }

}