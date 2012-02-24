<?php

/**
 * @file
 * @brief Holds a class to set the configuration values for php.ini
 */

namespace SprayFire\Bootstrap;

/**
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Util.CoreObject
 */
class IniConfigBootstrap extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief Will set the global ini configuration settings and either the development
     * or production ini configuration.
     *
     * @return void
     */
    public function runBootstrap() {

    }

}