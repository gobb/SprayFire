<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of FireDispatcherTest
 */

namespace SprayFire\Test\Cases\Dispatcher;

use \SprayFire\Dispatcher\FireDispatcher as FireDispatcher;

class FireDispatcherTest extends \PHPUnit_Framework_TestCase {

    protected $Cache;

    protected $Container;

    protected $Logger;

    public function setUp() {
        $this->Cache = new \Artax\ReflectionCacher();
        $this->Container = new \SprayFire\Service\FireBox\Container($this->Cache);
        $this->Container->addService('SprayFire.FileSys.Paths', function () {
            $RootPaths = new \SprayFire\FileSys\RootPaths(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework');
            return array($RootPaths);
        });
        $Error = $Emergency = $Debug = $Info = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $this->Logger = new \SprayFire\Logging\Logifier\LogDelegator($Emergency, $Error, $Debug, $Info);
    }

    public function testFireDispatcherTest() {
        $Router = $this->getRouter('');
        $ControllerFactory = $this->getControllerFactory();
        $ResponderFactory = $this->getResponderFactory();
        $Dispatcher = new \SprayFire\Dispatcher\FireDispatcher($Router, $ControllerFactory, $ResponderFactory);
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest(''));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherWithInvalidAction() {
        $Router = $this->getRouter('');
        $ControllerFactory = $this->getControllerFactory();
        $ResponderFactory = $this->getResponderFactory();
        $Dispatcher = new \SprayFire\Dispatcher\FireDispatcher($Router, $ControllerFactory, $ResponderFactory);
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/test-pages/non-existent'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '';
        $this->assertSame($expected, $response);
    }

    protected function getRequest($uri) {
        $_server = array();
        $_server['REQUEST_URI'] = $uri;
        $ResourceIdentifier = new \SprayFire\Http\ResourceIdentifier($_server);
        $Headers = new \SprayFire\Http\StandardRequestHeaders();
        return new \SprayFire\Http\StandardRequest($ResourceIdentifier, $Headers);
    }

    protected function getRouter($installDir) {
        $Normalizer = new \SprayFire\Http\Routing\Normalizer();
        $RootPaths = new \SprayFire\FileSys\RootPaths(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework');
        $Paths = new \SprayFire\FileSys\Paths($RootPaths);
        $configPath = $Paths->getConfigPath('SprayFire', 'routes.json');
        return new \SprayFire\Http\Routing\StandardRouter($Normalizer, $Paths, $configPath, $installDir);
    }

    protected function getControllerFactory() {
        return new \SprayFire\Controller\Factory($this->Cache, $this->Container, $this->Logger);
    }

    protected function getResponderFactory() {
        return new \SprayFire\Responder\Factory($this->Cache, $this->Container, $this->Logger);
    }

}
