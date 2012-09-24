<?php

/**
 * Implementation of SprayFire.Bootstrapper.Bootstrapper that sets variable php.ini
 * configuration properties during framework initialization.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Bootstrap\FireBootstrap;

use \SprayFire\Bootstrap as SFBootstrap,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * This bootstrap should be ran by init.php during framework initialization; to alter
 * the ini values that are set please reference install_dir/config/SprayFire/environment.php.
 *
 * @package SprayFire
 * @subpackage Bootstrap.FireBootstrap
 */
class IniSetting extends SFCoreObject implements SFBootstrap\Bootstrapper {

    /**
     * Associative array holding the settings and values that should be used in
     * the ini_set() call, [setting => value]
     *
     * @property array
     */
    protected $config;

    /**
     * The array should be associative with [ini_setting => value]
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Sets the ini configuration provided at object construction
     *
     * @return void
     */
    public function runBootstrap() {
        foreach ($this->config as $setting => $value) {
            \ini_set($setting, $value);
        }
    }

}