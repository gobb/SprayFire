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

    protected $JavaNameConverter;

    /**
     * @property SprayFire.Test.Helpers.DevelopmentLogger
     */
    protected $ErrorLog;

    public function setUp() {
        $this->Cache = new \Artax\ReflectionCacher();
        $this->Container = new \SprayFire\Service\FireBox\Container($this->Cache);
        $this->Container->addService('SprayFire.FileSys.Paths', function () {
            $RootPaths = new \SprayFire\FileSys\RootPaths(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework');
            return array($RootPaths);
        });
        $Emergency = $Debug = $Info = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $this->ErrorLog = $Error = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $this->Logger = new \SprayFire\Logging\Logifier\LogDelegator($Emergency, $Error, $Debug, $Info);
        $this->JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
    }

    public function testFireDispatcherTest() {
        $Router = $this->getRouter('');
        $Logger = $this->Logger;
        $ControllerFactory = $this->getControllerFactory();
        $ResponderFactory = $this->getResponderFactory();
        $Dispatcher = new \SprayFire\Dispatcher\FireDispatcher($Router, $Logger, $ControllerFactory, $ResponderFactory);
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest(''));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherWithInvalidAction() {
        $Router = $this->getRouter('');
        $Logger = $this->Logger;
        $ControllerFactory = $this->getControllerFactory();
        $ResponderFactory = $this->getResponderFactory();
        $Dispatcher = new \SprayFire\Dispatcher\FireDispatcher($Router, $Logger, $ControllerFactory, $ResponderFactory);
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/test-pages/non-existent'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>The action, nonExistent, was not found in, SprayFire.Test.Helpers.Controller.TestPages.</div>';
        $this->assertSame($expected, $response);
        $expectedErrorMessages = array(
            array(
                'message' => 'The action, nonExistent, was not found in, SprayFire.Test.Helpers.Controller.TestPages.',
                'options' => array()
            )
        );
        $actualErrorMessage = $this->ErrorLog->getLoggedMessages();
        $this->assertSame($expectedErrorMessages, $actualErrorMessage);
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
        return new \SprayFire\Controller\FireController\Factory($this->Cache, $this->Container, $this->Logger, $this->JavaNameConverter, 'SprayFire.Controller.Controller', 'SprayFire.Test.Helpers.Controller.DispatchNullObject');
    }

    protected function getResponderFactory() {
        return new \SprayFire\Responder\Factory($this->Cache, $this->Container, $this->Logger, $this->JavaNameConverter);
    }

}
