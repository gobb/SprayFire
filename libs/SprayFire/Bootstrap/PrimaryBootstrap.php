<?php

/**
 * @file
 * @brief Will instantiate and run other bootstraps, creating a DI container
 * for SprayFire components.
 */

namespace SprayFire\Bootstrap;

/**
 * @brief
 */
class PrimaryBootstrap extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief SprayFire.Bootstrap.BootstrapData object passed from constructor
     * holding the information needed to run secondary bootstraps
     *
     * @var $Data
     */
    protected $Data;

    /**
     * @brief
     *
     * @param $Data SprayFire.Bootstrap.BootstrapData
     */
    public function __construct(\SprayFire\Bootstrap\BootstrapData $Data) {
        $this->Data = $Data;
    }

    public function runBootstrap() {
        
    }

}