<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace TestApp;

class Bootstrap extends \SprayFire\StdLib\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    protected $Container;

    protected $ClassLoader;

    public function __construct(\SprayFire\Service\Container $Container, \ClassLoader\Loader $ClassLoader) {
        $this->Container = $Container;
        $this->ClassLoader = $ClassLoader;
    }

    public function runBootstrap() {
        $this->Container->addService('TestApp.Service.FromBootstrap');
    }


}
