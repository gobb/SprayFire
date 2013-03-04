<?php

/**
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Dispatcher\FireDispatcher;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Controller as SFController,
    \SprayFire\Responder as SFResponder,
    \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\Dispatcher\FireDispatcher as FireDispatcher,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Dispatcher.FireDispatcher
 */
class DispatcherTest extends PHPUnitTestCase {

    public function testFireDispatcherWithValidRoute() {
        $controller = 'SprayFire.Test.Helpers.Controller.TestPages';
        $action = 'indexYoDog';
        $parameters = array();
        $responderName = 'SprayFire.Responder.FireResponder.Html';

        $RoutedRequest = $this->getMockRoutedRequest($controller, $action, $parameters);
        $Mediator = $this->getMock('\\SprayFire\\Mediator\\Mediator');
        $Controller = $this->getMockController($action, $responderName, array($action));
        $ControllerFactory = $this->getMockControllerFactory($Controller, $controller);
        $Responder = $this->getMockResponder($Controller, '<div>SprayFire</div>');
        $ResponderFactory = $this->getMockResponderFactory($Responder, $responderName);

        $Dispatcher = new FireDispatcher\Dispatcher($Mediator, $ControllerFactory, $ResponderFactory);

        \ob_start();
        $Dispatcher->dispatchResponse($RoutedRequest);
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    public function testDispatcherTriggeringAppropriateEvents() {
        $controller = 'SprayFire.Test.Helpers.Controller.TestPages';
        $action = 'indexYoDog';
        $parameters = array();
        $responderName = 'SprayFire.Responder.FireResponder.Html';
        
        $RoutedRequest = $this->getMockRoutedRequest($controller, $action, $parameters);
        $RoutedRequest->expects($this->once())
                          ->method('getController')
                          ->will($this->returnValue('SprayFire.Test.Helpers.Controller.TestPages'));
        $RoutedRequest->expects($this->exactly(2))
                          ->method('getAction')
                          ->will($this->returnValue('indexYoDog'));

        $Controller = $this->getMockController($action, $responderName, array($action));
        $Responder = $this->getMockResponder($Controller, '<div>SprayFire</div>');

        $Mediator = $this->getMock('\\SprayFire\\Mediator\\Mediator');
        $Mediator->expects($this->exactly(4))
                     ->method('triggerEvent');
        $Mediator->expects($this->at(2))
                     ->method('triggerEvent')
                     ->with(\SprayFire\Events::BEFORE_CONTROLLER_INVOKED, $Controller);
        $Mediator->expects($this->at(3))
                     ->method('triggerEvent')
                     ->with(\SprayFire\Events::AFTER_CONTROLLER_INVOKED, $Controller);
        $Mediator->expects($this->at(4))
                     ->method('triggerEvent')
                     ->with(\SprayFire\Events::BEFORE_RESPONSE_SENT, $Responder);
        $Mediator->expects($this->at(5))
                     ->method('triggerEvent')
                     ->with(\SprayFire\Events::AFTER_RESPONSE_SENT, $Responder);

        $ControllerFactory = $this->getMockControllerFactory($Controller, $controller);
        $ResponderFactory = $this->getMockResponderFactory($Responder, $responderName);

        $Dispatcher = new FireDispatcher\Dispatcher($Mediator, $ControllerFactory, $ResponderFactory);

        \ob_start();
        $Dispatcher->dispatchResponse($RoutedRequest);
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);
    }

    public function testDispatcherAddingControllerBeforeActionCallback() {
        $controller = 'SprayFire.Test.Helpers.Controller.TestPages';
        $action = 'indexYoDog';
        $parameters = array();
        $responderName = 'SprayFire.Responder.FireResponder.Html';

        $RoutedRequest = $this->getMockRoutedRequest($controller, $action, $parameters);
        $Mediator = $this->getMock('\\SprayFireTest\\Dispatcher\\FireDispatcher\\DispatcherCallbackMediator',
                                       array(
                                            'triggerEvent',
                                            'removeCallback'
                                       ));
        $Controller = $this->getMockController($action, $responderName, array($action));
        $ControllerFactory = $this->getMockControllerFactory($Controller, $controller);
        $Responder = $this->getMockResponder($Controller, '<div>SprayFire</div>');
        $ResponderFactory = $this->getMockResponderFactory($Responder, $responderName);

        $Dispatcher = new FireDispatcher\Dispatcher($Mediator, $ControllerFactory, $ResponderFactory);

        \ob_start();
        $Dispatcher->dispatchResponse($RoutedRequest);
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);

        $BeforeControllerCallback = $Mediator->getCallbacks(\SprayFire\Events::BEFORE_CONTROLLER_INVOKED);
        $this->assertInstanceOf('\SprayFire\Mediator\Callback', $BeforeControllerCallback);
        $FunctionValue = $this->getFunctionPropertyValue($BeforeControllerCallback);
        $this->assertSame(array($Controller, 'beforeAction'), $FunctionValue);
    }

    public function testAddingControllerAfterActionCallbackToMediator() {
        $controller = 'SprayFire.Test.Helpers.Controller.TestPages';
        $action = 'indexYoDog';
        $parameters = array();
        $responderName = 'SprayFire.Responder.FireResponder.Html';

        $RoutedRequest = $this->getMockRoutedRequest($controller, $action, $parameters);
        $Mediator = $this->getMock('\\SprayFireTest\\Dispatcher\\FireDispatcher\\DispatcherCallbackMediator',
                                       array(
                                            'triggerEvent',
                                            'removeCallback'
                                       ));
        $Controller = $this->getMockController($action, $responderName, array($action));
        $ControllerFactory = $this->getMockControllerFactory($Controller, $controller);
        $Responder = $this->getMockResponder($Controller, '<div>SprayFire</div>');
        $ResponderFactory = $this->getMockResponderFactory($Responder, $responderName);

        $Dispatcher = new FireDispatcher\Dispatcher($Mediator, $ControllerFactory, $ResponderFactory);

        \ob_start();
        $Dispatcher->dispatchResponse($RoutedRequest);
        $response = \ob_get_contents();
        \ob_end_clean();
        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $response);

        $AfterControllerCallback = $Mediator->getCallbacks(\SprayFire\Events::AFTER_CONTROLLER_INVOKED);
        $this->assertInstanceOf('\SprayFire\Mediator\Callback', $AfterControllerCallback);
        $FunctionValue = $this->getFunctionPropertyValue($AfterControllerCallback);
        $this->assertSame(array($Controller, 'afterAction'), $FunctionValue);
    }

    public function testThrowingRightExceptionWhenControllerDoesNotHaveAction() {
        $controller = 'SprayFire.Test.Helpers.Controller.TestPages';
        $action = 'doesNotExist';
        $parameters = array();

        $RoutedRequest = $this->getMockRoutedRequest($controller, $action, $parameters, 1, 0);
        $Mediator = $this->getMock('\SprayFire\Mediator\Mediator');
        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $ControllerFactory = $this->getMockControllerFactory($Controller, $controller);
        $ResponderFactory = $this->getMock('\SprayFire\Factory\Factory');

        $Dispatcher = new FireDispatcher\Dispatcher($Mediator, $ControllerFactory, $ResponderFactory);
        $this->setExpectedException('\SprayFire\Dispatcher\Exception\ActionNotFound');
        $Dispatcher->dispatchResponse($RoutedRequest);
    }

    protected function getFunctionPropertyValue(SFMediator\Callback $Callback) {
        $ReflectedCallback = new \ReflectionObject($Callback);
        try {
            $FunctionProperty = $ReflectedCallback->getProperty('function');
            $FunctionProperty->setAccessible(true);
            return $FunctionProperty->getValue($Callback);
        } catch(\ReflectionException $ReflectionException) {
            return null;
        }
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
            'getTemplateManager',
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


    protected function getMockRoutedRequest($controller, $action, $parameters, $numGetAction = 2, $numGetParameters = 1) {
        $MockRoutedRequest = $this->getMock('\\SprayFire\\Http\\Routing\\RoutedRequest');
        $MockRoutedRequest->expects($this->once())
                          ->method('getController')
                          ->will($this->returnValue($controller));
        $MockRoutedRequest->expects($this->exactly($numGetAction))
                          ->method('getAction')
                          ->will($this->returnValue($action));
        $MockRoutedRequest->expects($this->exactly($numGetParameters))
                          ->method('getParameters')
                          ->will($this->returnValue($parameters));
        return $MockRoutedRequest;
    }

}

/**
 * A stub of the SprayFire.Mediator.Mediator interface to help testing that the
 * Dispatcher adds the appropriate callbacks during request processing.
 */
abstract class DispatcherCallbackMediator implements SFMediator\Mediator {

    protected $addedCallback = array();

    public function addCallback(SFMediator\Callback $Callback) {
        $this->addedCallback[$Callback->getEventName()] = $Callback;
    }

    public function getCallbacks($eventName) {
        return $this->addedCallback[$eventName];
    }

}
