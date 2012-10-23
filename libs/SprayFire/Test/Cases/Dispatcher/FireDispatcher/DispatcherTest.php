<?php

/**
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Dispatcher\FireDispatcher;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Controller as SFController,
    \SprayFire\Responder as SFResponder,
    \SprayFire\Dispatcher\FireDispatcher as FireDispatcher,
    \SprayFire\Mediator\DispatcherEvents as DispatcherEvents;

/**
 * @package SprayFireTest
 * @subpackage Cases.Dispatcher.FireDispatcher
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase {

    public function testFireDispatcherWithValidRoute() {
        $controller = 'SprayFire.Test.Helpers.Controller.TestPages';
        $action = 'indexYoDog';
        $parameters = array();
        $responderName = 'SprayFire.Responder.FireResponder.Html';

        $MockRoutedRequest = $this->getMockRoutedRequest($controller, $action, $parameters);
        $MockRouter = $this->getMockRouter($MockRoutedRequest);
        $MockMediator = $this->getMock('\\SprayFire\\Mediator\\Mediator');
        $MockAppInitializer = $this->getMock('\\SprayFire\\Dispatcher\\AppInitializer');
        $MockController = $this->getMockController($action, $responderName, array($action));
        $MockControllerFactory = $this->getMockControllerFactory($MockController, $controller);
        $MockResponder = $this->getMockResponder($MockController, '<div>SprayFire</div>');
        $MockResponderFactory = $this->getMockResponderFactory($MockResponder, $responderName);

        $Dispatcher = new FireDispatcher\Dispatcher(
            $MockRouter,
            $MockMediator,
            $MockAppInitializer,
            $MockControllerFactory,
            $MockResponderFactory
        );

        \ob_start();
        $Dispatcher->dispatchResponse($this->getRequest(''));
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    /**
     * Will create a mock of a SprayFire.Factory.Factory that will return a
     * $MockController when Factory::makeObject is called with $controllerName
     * as the argument.
     *
     * @param SprayFire.Controller.Controller $MockController
     * @param string $controllerName
     * @return SprayFire.Factory.Factory
     */
    protected function getMockControllerFactory(SFController\Controller $MockController, $controllerName) {
        $MockControllerFactory = $this->getMock('\\SprayFire\\Factory\\Factory');
        $MockControllerFactory->expects($this->once())
                              ->method('makeObject')
                              ->with($this->equalTo($controllerName))
                              ->will($this->returnValue($MockController));
        return $MockControllerFactory;
    }

    /**
     * Will create a mock of a SprayFire.Controller.Controller that will expect
     * the appropriate methods for normal dispatcher processing and will
     *
     * @param string $action
     * @param string $responderName
     * @param array $extraMethods
     * @return SprayFire.Controller.Controller
     */
    protected function getMockController($action, $responderName, array $extraMethods = array()) {
        $defaultMethods = array(
            'getResponderName',
            'getTemplatePath',
            'getLayoutPath',
            'setResponderData',
            'setMultipleResponderData',
            'getResponderData',
            'beforeAction',
            'afterAction',
            'getRequestedServices',
            'giveService',
            'equals',
            'hashCode',
            '__toString'
        );
        $methods = \array_merge($defaultMethods, $extraMethods);
        $MockController = $this->getMock('\\SprayFire\\Controller\\Controller', $methods);
        $MockController->expects($this->once())
                       ->method($action);
        $MockController->expects($this->once())
                       ->method('getResponderName')
                       ->will($this->returnValue($responderName));
        return $MockController;
    }

    /**
     *
     * @param SprayFire.Controller.Controller $MockController
     * @param string $returnContent
     * @return SprayFire.Responder.Responder
     */
    protected function getMockResponder(SFController\Controller $MockController, $returnContent) {
        $MockResponder = $this->getMock('\\SprayFire\\Responder\\Responder');
        $MockResponder->expects($this->once())
                      ->method('generateDynamicResponse')
                      ->with($this->equalTo($MockController))
                      ->will($this->returnValue($returnContent));
        return $MockResponder;
    }

    protected function getMockResponderFactory(SFResponder\Responder $MockResponder, $responderName) {
        $MockResponderFactory = $this->getMock('\\SprayFire\\Factory\\Factory');
        $MockResponderFactory->expects($this->once())
                             ->method('makeObject')
                             ->with($this->equalTo($responderName))
                             ->will($this->returnValue($MockResponder));
        return $MockResponderFactory;
    }


    protected function getMockRoutedRequest($controller, $action, $parameters) {
        $MockRoutedRequest = $this->getMock('\\SprayFire\\Http\\Routing\\RoutedRequest');
        $MockRoutedRequest->expects($this->once())
                          ->method('getController')
                          ->will($this->returnValue($controller));
        $MockRoutedRequest->expects($this->exactly(2))
                          ->method('getAction')
                          ->will($this->returnValue($action));
        $MockRoutedRequest->expects($this->once())
                          ->method('getParameters')
                          ->will($this->returnValue($parameters));
        return $MockRoutedRequest;
    }

    protected function getMockRouter(SFRouting\RoutedRequest $MockRoutedRequest) {
        $MockRouter = $this->getMock('\\SprayFire\\Http\\Routing\\Router');
        $MockRouter->expects($this->once())->method('getRoutedRequest')->will($this->returnValue($MockRoutedRequest));
        return $MockRouter;
    }

    protected function getRequest($uri) {
        $_server = array();
        $_server['REQUEST_URI'] = $uri;
        $Uri = new \SprayFire\Http\FireHttp\Uri($_server);
        $Headers = new \SprayFire\Http\FireHttp\RequestHeaders();
        $Request = new \SprayFire\Http\FireHttp\Request($Uri, $Headers);
        return $Request;
    }

}
