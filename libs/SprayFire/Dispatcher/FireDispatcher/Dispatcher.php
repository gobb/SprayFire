<?php

/**
 * Implementation of \SprayFire\Dispatcher\Dispatcher provided with the default
 * SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher\FireDispatcher;

use \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\Http\Routing as SFHttpRouting,
    \SprayFire\Factory as SFFactory,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\Controller as SFController,
    \SprayFire\StdLib as SFStdLib,
    \SprayFire\Mediator\FireMediator as FireMediator;

/**
 * This class is the primary workhorse of the framework and is responsible for
 * the overall resource processing logic including:
 *
 * - Routing the given \SprayFire\Http\Request
 * - Initializing the appropriate application based on routing
 * - Creation of the appropriate \SprayFire\Controller\Controller and \SprayFire\Responder\Responder
 * objects.
 * - Invocation of the Controller and the triggering of an events provided by
 * the default SprayFire install
 *
 * @package SprayFire
 * @subpackage Dispatcher.FireDispatcher
 */
class Dispatcher extends SFStdLib\CoreObject implements SFDispatcher\Dispatcher {

    /**
     * Used to ensure the appropriate applications gets autoloaded and bootstrapped.
     *
     * @property \SprayFire\Dispatcher\AppInitializer
     */
    protected $AppInitializer;

    /**
     * Is used to ensure that appropriate events for the dispatching process are
     * triggered when appropriate.
     *
     * @property \SprayFire\Mediator\Mediator
     */
    protected $Mediator;

    /**
     * Ensures an appropriate \SprayFire\Controller\Controller can be created.
     *
     * @property \SprayFire\Factory\Factory
     */
    protected $ControllerFactory;

    /**
     * Ensures an appropriate \SprayFire\Responder\Responder can be created.
     *
     * @property \SprayFire\Factory\Factory
     */
    protected $ResponderFactory;

    /**
     * A configuration array, the default environment configuration for a SprayFire
     * install can be found in install_dir/config/SprayFire/environment.php
     *
     * @property array
     */
    protected $environmentConfig;

    /**
     * @param \SprayFire\Mediator\Mediator $Mediator
     * @param \SprayFire\Factory\Factory $ControllerFactory
     * @param \SprayFire\Factory\Factory $ResponderFactory
     */
    public function __construct(SFMediator\Mediator $Mediator, SFFactory\Factory $ControllerFactory, SFFactory\Factory $ResponderFactory) {
        $this->Mediator = $Mediator;
        $this->ControllerFactory = $ControllerFactory;
        $this->ResponderFactory = $ResponderFactory;
    }

    /**
     * Will route a SprayFire.Http.Request object, triggering appropriate Dispatcher
     * events along the way, and start the invocation of the static or dynamic
     * response sending.
     *
     * @param \SprayFire\Http\Routing\RoutedRequest $RoutedRequest
     * @return void
     * @throws \SprayFire\Dispatcher\Exception\ActionNotFound
     */
    public function dispatchResponse(SFHttpRouting\RoutedRequest $RoutedRequest) {
        $Controller = $this->generateController($RoutedRequest->getController(), $RoutedRequest->getAction());
        $this->addControllerBeforeActionEventToMediator($Controller);
        $this->addControllerAfterActionEventToMediator($Controller);

        $this->invokeController($Controller, $RoutedRequest);

        $ResponderName = $Controller->getResponderName();
        $Responder = $this->ResponderFactory->makeObject($ResponderName);
        $this->Mediator->triggerEvent(SFDispatcher\Events::BEFORE_RESPONSE_SENT, $Responder);
        echo $Responder->generateDynamicResponse($Controller);
        $this->Mediator->triggerEvent(SFDispatcher\Events::AFTER_RESPONSE_SENT, $Responder);
    }

    /**
     * Will create a callback for the Controller::beforeAction method and add that
     * to the Mediator to be invoked as appropriate.
     *
     * @param \SprayFire\Controller\Controller $Controller
     */
    protected function addControllerBeforeActionEventToMediator(SFController\Controller $Controller) {
        $event = SFDispatcher\Events::BEFORE_CONTROLLER_INVOKED;
        $function = [$Controller, 'beforeAction'];
        $Callback = new FireMediator\Callback($event, $function);
        $this->Mediator->addCallback($Callback);
    }

    /**
     * Will create a callback for the Controller::afterAction method and add that
     * to the Mediator to be invoked as appropriate.
     *
     * @param \SprayFire\Controller\Controller $Controller
     */
    protected function addControllerAfterActionEventToMediator(SFController\Controller $Controller) {
        $event = SFDispatcher\Events::AFTER_CONTROLLER_INVOKED;
        $function = [$Controller, 'afterAction'];
        $Callback = new FireMediator\Callback($event, $function);
        $this->Mediator->addCallback($Callback);
    }

    /**
     * Generates the appropriate controller and, if the method exists, will return
     * the implementation.
     *
     * An exception will be thrown if the $actionName does not exist as a callable
     * method on created controller.
     *
     * @param string $controllerName
     * @param string $actionName
     * @return \SprayFire\Controller\Controller
     * @throws \SprayFire\Dispatcher\Exception\ActionNotFound
     */
    protected function generateController($controllerName, $actionName) {
        $Controller = $this->ControllerFactory->makeObject($controllerName);
        if (!\method_exists($Controller, $actionName)) {
            throw new SFDispatcher\Exception\ActionNotFound('The given object ' . $controllerName . ' does not have the requested action ' . $actionName);
        }
        return $Controller;
    }

    /**
     * Ensures that the appropriate events and actions for invoking a \SprayFire\Controller\Controller
     * are properly carried out.
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @param \SprayFire\Http\Routing\RoutedRequest $RoutedRequest
     * @return void
     */
    protected function invokeController(SFController\Controller $Controller, SFHttpRouting\RoutedRequest $RoutedRequest) {
        $this->Mediator->triggerEvent(SFDispatcher\Events::BEFORE_CONTROLLER_INVOKED, $Controller);
        $actionName = $RoutedRequest->getAction();
        $parameters = $RoutedRequest->getParameters();
        \call_user_func_array([$Controller, $actionName], $parameters);
        $this->Mediator->triggerEvent(SFDispatcher\Events::AFTER_CONTROLLER_INVOKED, $Controller);
    }

}
