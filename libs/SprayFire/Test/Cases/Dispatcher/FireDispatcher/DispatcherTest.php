<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of FireDispatcherTest
 */

namespace SprayFire\Test\Cases\Dispatcher\FireDispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase {

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
        $Emergency = $Debug = $Info = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $this->ErrorLog = $Error = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $this->Logger = new \SprayFire\Logging\FireLogging\LogDelegator($Emergency, $Error, $Debug, $Info);
        $this->JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $this->Container = new \SprayFire\Service\FireService\Container($this->Cache, $this->JavaNameConverter);
        $this->Container->addService('SprayFire.FileSys.FireFileSys.Paths', function () {
            $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework');
            return array($RootPaths);
        });
        $this->Container->addService($this->Logger);
    }

    public function testFireDispatcherTest() {
        $Request = $this->getRequest('');
        $Router = $this->getRouter('');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->Container->addService($Request);
        $this->Container->addService($RoutedRequest);
        $Logger = $this->Logger;
        $ControllerFactory = $this->getControllerFactory();
        $ResponderFactory = $this->getResponderFactory();
        $Dispatcher = new \SprayFire\Dispatcher\FireDispatcher\Dispatcher($Router, $Logger, $ControllerFactory, $ResponderFactory);
        \ob_start();
        $Dispatcher->dispatchResponse($Request);
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherWithInvalidAction() {
        $Request = $this->getRequest('/test-pages/non-existent');
        $Router = $this->getRouter('');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $Logger = $this->Logger;
        $this->Container->addService($Request);
        $this->Container->addService($RoutedRequest);
        $ControllerFactory = $this->getControllerFactory();
        $ResponderFactory = $this->getResponderFactory();
        $Dispatcher = new \SprayFire\Dispatcher\FireDispatcher\Dispatcher($Router, $Logger, $ControllerFactory, $ResponderFactory);
        \ob_start();
        $Dispatcher->dispatchResponse($Request);
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
        $Uri = new \SprayFire\Http\FireHttp\Uri($_server);
        $Headers = new \SprayFire\Http\FireHttp\RequestHeaders();
        return new \SprayFire\Http\FireHttp\Request($Uri, $Headers);
    }

    protected function getRouter($installDir) {
        $Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
        $config = array(
            'routes' => array(
                '/' => array(
                    'namespace' => 'SprayFire.Test.Helpers.Controller',
                    'controller' => 'TestPages',
                    'action' => 'index-yo-dog',
                    'parameters' => array(
                        'yo',
                        'dog'
                    )
                ),
                '/test-pages/non-existent/' => array(
                    'namespace' => 'SprayFire.Test.Helpers.Controller',
                    'controller' => 'TestPages',
                    'action' => 'non-existent'
                )
            )
        );
        return new \SprayFire\Http\Routing\FireRouting\Router($Normalizer, $config, $installDir);
    }

    protected function getControllerFactory() {
        return new \SprayFire\Controller\FireController\Factory($this->Cache, $this->Container, $this->Logger, $this->JavaNameConverter, 'SprayFire.Controller.Controller', 'SprayFire.Test.Helpers.Controller.DispatchNullObject');
    }

    protected function getResponderFactory() {
        return new \SprayFire\Responder\Factory($this->Cache, $this->Container, $this->Logger, $this->JavaNameConverter);
    }

}
