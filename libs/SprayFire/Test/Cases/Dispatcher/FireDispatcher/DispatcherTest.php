<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of FireDispatcherTest
 */

namespace SprayFire\Test\Cases\Dispatcher\FireDispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase {

    protected $Container;

    protected $environmentConfig;

    public $routeConfig;

    /**
     * @property SprayFire.Test.Helpers.DevelopmentLogger
     */
    protected $ErrorLog;

    public function setUp() {
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaNameConverter);
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache);

        $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework');
        $Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);

        $Container->addService($JavaNameConverter);
        $Container->addService($ReflectionCache);
        $Container->addService($Paths);

        $this->routeConfig = array(
            '404' => array(
                'static' => true,
                'layoutPath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'just-templatecontents-around-div.php'),
                'templatePath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', '404.php'),
                'responderName' => 'SprayFire.Responder.HtmlResponder'
            ),
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
                ),
                '/nocontroller/' => array(
                    'namespace' => 'SprayFire.Test.Helpers.Controller',
                    'controller' => 'NoExist'
                ),
                '/initializer/' => array(
                    'namespace' => 'TestApp.Controller',
                    'controller' => 'Base',
                    'action' => 'index'
                )
            )
        );

        $that = $this;
        $environmentConfig = array(
            'services' => array(
                'HttpRouter' => array(
                    'name' => 'SprayFire.Http.Routing.FireRouting.Router',
                    'parameterCallback' => function() use ($Paths, $that) {
                        $Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
                        $installDir = \basename($Paths->getInstallPath());
                        return array($Normalizer, $that->routeConfig, $installDir);
                    }
                ),
                'Logging' => array(
                    'name' => 'SprayFire.Logging.FireLogging.LogDelegator',
                    'parameterCallback' => function() {
                        $Logger = new \SprayFire\Logging\FireLogging\DevelopmentLogger();
                        return array($Logger, $Logger, $Logger, $Logger);
                    }
                ),
                'ControllerFactory' => array(
                    'name' => 'SprayFire.Controller.FireController.Factory',
                    'parameterCallback' => function() use ($ReflectionCache, $Container) {
                        $Logger = $Container->getService('SprayFire.Logging.FireLogging.LogDelegator');
                        return array($ReflectionCache, $Container, $Logger);
                    }
                ),
                'ResponderFactory' => array(
                    'name' => 'SprayFire.Responder.Factory',
                    'parameterCallback' => function() use ($ReflectionCache, $Container) {
                        $Logger = $Container->getService('SprayFire.Logging.FireLogging.LogDelegator');
                        return array($ReflectionCache, $Container, $Logger);
                    }
                )
            )
        );

        foreach ($environmentConfig['services'] as $service) {
            $Container->addService($service['name'], $service['parameterCallback']);
        }

        $this->Container = $Container;
        $this->environmentConfig = $environmentConfig;
    }

    public function testFireDispatcherWithValidRoute() {
        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest(''));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherWithNonExistentRoute() {
        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/nonexistent/route'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div><p>404 Not Found</p></div>';
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherWithNotFoundController() {
        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/nocontroller'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div><p>404 Not Found</p></div>';
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherInitializingApp() {
        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/initializer'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>intiializer</div>';
        $this->assertTrue($this->Container->doesServiceExist('TestApp.Service.FromBootstrap'), 'Container does not have TestApp.Bootstrap added service');
    }

    protected function getRequest($uri) {
        $_server = array();
        $_server['REQUEST_URI'] = $uri;
        $Uri = new \SprayFire\Http\FireHttp\Uri($_server);
        $Headers = new \SprayFire\Http\FireHttp\RequestHeaders();
        return new \SprayFire\Http\FireHttp\Request($Uri, $Headers);
    }

    protected function getDispatcher() {
        $Initializer = $this->getAppInitializer();
        $Router = $this->Container->getService($this->environmentConfig['services']['HttpRouter']['name']);
        $ControllerFactory = $this->Container->getService($this->environmentConfig['services']['ControllerFactory']['name']);
        $ControllerFactory->setErrorHandlingMethod(\SprayFire\Factory\FireFactory\Base::THROW_EXCEPTION);
        $ResponderFactory = $this->Container->getService($this->environmentConfig['services']['ResponderFactory']['name']);
        return new \SprayFire\Dispatcher\FireDispatcher\Dispatcher($Router, $Initializer, $ControllerFactory, $ResponderFactory);
    }

    protected function getAppInitializer() {
        $installDir = \SPRAYFIRE_ROOT;
        $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($installDir);
        $Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaNameConverter);
        $ClassLoader = new \ClassLoader\Loader();
        return new \SprayFire\Dispatcher\FireDispatcher\AppInitializer($this->Container, $ClassLoader, $Paths);
    }

}
