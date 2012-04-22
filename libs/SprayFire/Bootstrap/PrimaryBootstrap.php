<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Bootstrap;

/**
 * @brief
 */
class PrimaryBootstrap extends \SprayFire\Util\CoreObject {

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

}