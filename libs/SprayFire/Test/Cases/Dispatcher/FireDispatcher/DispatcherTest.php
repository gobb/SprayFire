<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of FireDispatcherTest
 */

namespace SprayFire\Test\Cases\Dispatcher\FireDispatcher;

use \SprayFire\Mediator\DispatcherEvents as DispatcherEvents;

class DispatcherTest extends \PHPUnit_Framework_TestCase {

    public $Container;

    public $JavaNameConverter;

    public $ReflectionCache;

    public $environmentConfig;

    public $Paths;

    public $Mediator;

    public $EventRegistry;

    public $routeConfig;

    /**
     * @property SprayFire.Test.Helpers.DevelopmentLogger
     */
    protected $ErrorLog;

    public function setUp() {
        $this->JavaNameConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $this->ReflectionCache = new \SprayFire\Utils\ReflectionCache($this->JavaNameConverter);
        $Container = new \SprayFire\Service\FireService\Container($this->ReflectionCache);

        $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework');
        $this->Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);

        $Container->addService($this->JavaNameConverter);
        $Container->addService($this->ReflectionCache);
        $Container->addService($this->Paths);

        $this->routeConfig = array(
            '404' => array(
                'static' => true,
                'layoutPath' => $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'just-templatecontents-around-div.php'),
                'templatePath' => $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', '404.php'),
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
                ),
                '/noaction/' => array(
                    'namespace' => 'SprayFire.Test.Helpers.Controller',
                    'controller' => 'TestPages',
                    'action' => 'likelyThatThisDoesNotExist'
                )
            )
        );

        $that = $this;
        $environmentConfig = array(
            'services' => array(
                'HttpRouter' => array(
                    'name' => 'SprayFire.Http.Routing.FireRouting.Router',
                    'parameterCallback' => function() use ($that) {
                        $Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
                        $installDir = \basename($that->Paths->getInstallPath());
                        return array($Normalizer, $that->routeConfig, $installDir);
                    }
                ),
                'Logging' => array(
                    'name' => 'SprayFire.Logging.FireLogging.LogOverseer',
                    'parameterCallback' => function() {
                        $Logger = new \SprayFire\Logging\FireLogging\DevelopmentLogger();
                        return array($Logger, $Logger, $Logger, $Logger);
                    }
                ),
                'ControllerFactory' => array(
                    'name' => 'SprayFire.Controller.FireController.Factory',
                    'parameterCallback' => function() use ($that) {
                        $Logger = $that->Container->getService('SprayFire.Logging.FireLogging.LogOverseer');
                        return array($that->ReflectionCache, $that->Container, $Logger);
                    }
                ),
                'ResponderFactory' => array(
                    'name' => 'SprayFire.Responder.FireResponder.Factory',
                    'parameterCallback' => function() use ($that) {
                        $Logger = $that->Container->getService('SprayFire.Logging.FireLogging.LogOverseer');
                        return array($that->ReflectionCache, $that->Container, $Logger);
                    }
                ),
                'HttpRequest' => array(
                    'name' => 'SprayFire.Http.FireHttp.Request',
                    'parameterCallback' => function() {
                        $Uri = new \SprayFire\Http\FireHttp\Uri();
                        $RequestHeader = new \SprayFire\Http\FireHttp\RequestHeaders();
                        return array($Uri, $ReflectionCache);
                    }
                ),
                'HttpRoutedRequest' => array(
                    'name' => 'SprayFire.Http.Routing.FireRouting.RoutedRequest',
                    'parameterCallback' => function() use($Container) {
                        return array('', '', array(), false);
                    }
                )
            )
        );

        foreach ($environmentConfig['services'] as $service) {
            $Container->addService($service['name'], $service['parameterCallback']);
        }

        $this->Container = $Container;
        $this->EventRegistry = new \SprayFire\Mediator\FireMediator\EventRegistry();

        $this->EventRegistry->registerEvent(DispatcherEvents::BEFORE_ROUTING, '');
        $this->EventRegistry->registerEvent(DispatcherEvents::AFTER_ROUTING, '');
        $this->EventRegistry->registerEvent(DispatcherEvents::BEFORE_CONTROLLER_INVOKED, '');
        $this->EventRegistry->registerEvent(DispatcherEvents::AFTER_CONTROLLER_INVOKED, '');
        $this->EventRegistry->registerEvent(DispatcherEvents::BEFORE_RESPONSE_SENT, '');
        $this->EventRegistry->registerEvent(DispatcherEvents::AFTER_RESPONSE_SENT, '');

        $this->Mediator = new \SprayFire\Mediator\FireMediator\Mediator($this->EventRegistry);
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

    public function testFireDispatcherWithControllerNotHavingAppropriateAction() {
        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/noaction'));
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
        $expected = '<div>initializer</div>';
        $this->assertTrue($this->Container->doesServiceExist('TestApp.Service.FromBootstrap'), 'Container does not have TestApp.Bootstrap added service');
        $this->assertSame($expected, $response);
    }

    public function testFireDispatcherTriggeringBeforeRoutingEvent() {
        $eventData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::BEFORE_ROUTING;
        $function = function($Event) use(&$eventData) {
            $eventData[$Event->getEventName()] = $Event->getTarget()->getUri()->getPath();
        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $this->Mediator->addCallback($Callback);

        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/initializer'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>initializer</div>';
        $this->assertSame($expected, $response);
        $this->assertSame($eventData[$eventName], '/initializer');
    }

    public function testFireDispatcherTriggeringAFterRoutingEvent() {
        $eventData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_ROUTING;
        $function = function($Event) use(&$eventData) {
            $eventData[$Event->getEventName()] = $Event->getTarget()->getController();
        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $this->Mediator->addCallback($Callback);

        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/initializer'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>initializer</div>';
        $this->assertSame($expected, $response);
        $this->assertSame($eventData[$eventName], 'TestApp.Controller.Base');
    }

    public function testFireDispatcherTriggeringBeforeControllerInvokedEvent() {
        $eventData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::BEFORE_CONTROLLER_INVOKED;
        $function = function($Event) use(&$eventData) {
            $eventData[$Event->getEventName()] = \get_class($Event->getTarget());
        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $this->Mediator->addCallback($Callback);

        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
        $this->assertSame($eventData[$eventName], 'SprayFire\\Test\\Helpers\\Controller\\TestPages');
    }

    public function testFireDispatcherTriggeringAfterControllerInvokedEvent() {
        $eventData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $function = function($Event) use(&$eventData) {
            $eventData[$Event->getEventName()] = \get_class($Event->getTarget());
        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $this->Mediator->addCallback($Callback);

        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
        $this->assertSame($eventData[$eventName], 'SprayFire\\Test\\Helpers\\Controller\\TestPages');
    }

    public function testFireDispatcherTriggeringBeforeResponseInvokedEvent() {
        $eventData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::BEFORE_RESPONSE_SENT;
        $function = function($Event) use(&$eventData) {
            $eventData[$Event->getEventName()] = \get_class($Event->getTarget());
        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $this->Mediator->addCallback($Callback);

        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
        $this->assertSame($eventData[$eventName], 'SprayFire\\Responder\\FireResponder\\Html');
    }

    public function testFireDispatcherTriggeringAfterResponseInvokedEvent() {
        $eventData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_RESPONSE_SENT;
        $function = function($Event) use(&$eventData) {
            $eventData[$Event->getEventName()] = \get_class($Event->getTarget());
        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $this->Mediator->addCallback($Callback);

        $Dispatcher = $this->getDispatcher();
        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest('/'));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
        $this->assertSame($eventData[$eventName], 'SprayFire\\Responder\\FireResponder\\Html');
    }



    protected function getRequest($uri) {
        $_server = array();
        $_server['REQUEST_URI'] = $uri;
        $Uri = new \SprayFire\Http\FireHttp\Uri($_server);
        $Headers = new \SprayFire\Http\FireHttp\RequestHeaders();
        $Request = new \SprayFire\Http\FireHttp\Request($Uri, $Headers);
        $this->Container->addService($Request);
        return $Request;
    }

    protected function getDispatcher() {
        $Initializer = $this->getAppInitializer();
        $Router = $this->Container->getService($this->environmentConfig['services']['HttpRouter']['name']);
        $ControllerFactory = $this->Container->getService($this->environmentConfig['services']['ControllerFactory']['name']);
        $ControllerFactory->setErrorHandlingMethod(\SprayFire\Factory\FireFactory\Base::THROW_EXCEPTION);
        $ResponderFactory = $this->Container->getService($this->environmentConfig['services']['ResponderFactory']['name']);
        return new \SprayFire\Dispatcher\FireDispatcher\Dispatcher($Router, $this->Mediator, $Initializer, $ControllerFactory, $ResponderFactory);
    }

    protected function getAppInitializer() {
        $ClassLoader = new \ClassLoader\Loader();
        $ClassLoader->setAutoloader();
        return new \SprayFire\Dispatcher\FireDispatcher\AppInitializer($this->Container, $ClassLoader, $this->Paths);
    }

}
