<?php

/**
 * Implementation of SprayFire.Dispatcher.Dispatcher provided with the default
 * SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher\FireDispatcher;

use \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\Service as SFService,
    \SprayFire\Http as SFHttp,
    \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Factory as SFFactory,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\Controller as SFController,
    \SprayFire\Exception as SFException,
    \SprayFire\CoreObject as SFCoreObject,
    \SprayFire\Mediator\DispatcherEvents as SFDispatcherEvents;

/**
 * This class is the primary workhorse of the framework and is responsible for
 * the overall resource processing logic including:
 *
 * - Routing the given SprayFire.Http.Request
 * - Initializing the appropriate application based on routing
 * - Creation of the appropriate SprayFire.Controller.Controller and SprayFire.Responder.Responder
 * objects.
 * - Invocation of the Controller and the triggering of an events provided by
 * the default SprayFire install
 *
 * @package SprayFire
 * @subpackage Dispatcher.FireDispatcher
 *
 * @TODO
 * We need to add event triggering to static responses.
 */
class Dispatcher extends SFCoreObject implements SFDispatcher\Dispatcher {

    /**
     * Used to ensure the appropriate applications gets autoloaded and bootstrapped.
     *
     * @property SprayFire.Dispatcher.AppInitializer
     */
    protected $AppInitializer;

    /**
     * Is used to get an appropriate SprayFire.Http.Routing.RoutedRequest to
     * know what controller/action to process.
     *
     * @property SprayFire.Http.Routing.Router
     */
    protected $Router;

    /**
     * Is used to ensure that appropriate events for the dispatching process are
     * triggered when appropriate.
     *
     * @property SprayFire.Mediator.Mediator
     */
    protected $Mediator;

    /**
     * Ensures an appropriate SprayFire.Controller.Controller can be created.
     *
     * @property SprayFire.Factory.Factory
     */
    protected $ControllerFactory;

    /**
     * Ensures an appropriate SprayFire.Responder.Responder can be created.
     *
     * @property SprayFire.Factory.Factory
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
     * @param SprayFire.Http.Routing.Router $Router
     * @param SprayFire.Mediator.Mediator $Mediator
     * @param SprayFire.Dispatcher.AppInitializer $AppInitializer
     * @param SprayFire.Factory.Factory $ControllerFactory
     * @param SprayFire.Factory.Factory $ResponderFactory
     */
    public function __construct(
        SFRouting\Router $Router,
        SFMediator\Mediator $Mediator,
        SFDispatcher\AppInitializer $AppInitializer,
        SFFactory\Factory $ControllerFactory,
        SFFactory\Factory $ResponderFactory
    ) {
        $this->Router = $Router;
        $this->Mediator = $Mediator;
        $this->AppInitializer = $AppInitializer;
        $this->ControllerFactory = $ControllerFactory;
        $this->ResponderFactory = $ResponderFactory;
    }

    /**
     * Will route a SprayFire.Http.Request object, triggering appropriate Dispatcher
     * events along the way, and start the invocation of the static or dynamic
     * response sending.
     *
     * @param SprayFire.Http.Request $Request
     */
    public function dispatchResponse(SFHttp\Request $Request) {
        $this->Mediator->triggerEvent(SFDispatcherEvents::BEFORE_ROUTING, $Request);
        $RoutedRequest = $this->Router->getRoutedRequest($Request);
        $this->Mediator->triggerEvent(SFDispatcherEvents::AFTER_ROUTING, $RoutedRequest);
        if ($RoutedRequest->isStatic()) {
            $this->sendStaticRequest($RoutedRequest);
        } else {
            $this->AppInitializer->initializeApp($RoutedRequest);
            $this->sendDynamicRequest($RoutedRequest);
        }
    }

    /**
     * Will get the appropriate static files for the provided SprayFire.Http.Routing.RoutedRequest
     * and will simply render the contents provided.
     *
     * This implementation does not do anything else, it is specfically intended
     * to be a shorter, less resource intensive means of sending content to the
     * user.  However this means that no data is passed to the Responder.
     *
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     */
    protected function sendStaticRequest(SFRouting\RoutedRequest $RoutedRequest) {
        $staticFiles = $this->Router->getStaticFilePaths($RoutedRequest);
        $Responder = $this->ResponderFactory->makeObject($staticFiles['responderName']);
        echo $Responder->generateStaticResponse($staticFiles['layoutPath'], $staticFiles['templatePath']);
    }

    /**
     * Will generate the appropriate Controller, invoke the appropriate actions
     * on that controller, generate the appropriate Responder and finally send
     * the response to the user; along the way appropriate SprayFire events will
     * be triggered.
     *
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     */
    protected function sendDynamicRequest(SFRouting\RoutedRequest $RoutedRequest) {
        try {
            $Controller = $this->generateController($RoutedRequest->getController(), $RoutedRequest->getAction());
            $this->invokeController($Controller, $RoutedRequest);
            $ResponderName = $Controller->getResponderName();
            $Responder = $this->ResponderFactory->makeObject($ResponderName);
            $this->Mediator->triggerEvent(SFDispatcherEvents::BEFORE_RESPONSE_SENT, $Responder);
            echo $Responder->generateDynamicResponse($Controller);
            $this->Mediator->triggerEvent(SFDispatcherEvents::AFTER_RESPONSE_SENT, $Responder);
        } catch(SFException\ResourceNotFound $TypeNotFoundExc) {
            $this->dispatch404Response();
        }
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
     * @return SprayFire.Controller.Controller
     * @throws SprayFire.Exception.ResourceNotFound
     */
    protected function generateController($controllerName, $actionName) {
        $Controller = $this->ControllerFactory->makeObject($controllerName);
        if (!\method_exists($Controller, $actionName)) {
            throw new SFException\ResourceNotFound('The given object ' . $controllerName . ' does not have the requested action ' . $actionName);
        }
        return $Controller;
    }

    /**
     * Ensures that the appropriate events and actions for invoking a SprayFire.Controller.Controller
     * are properly carried out.
     *
     * @param SprayFire.Controller.Controller $Controller
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return void
     */
    protected function invokeController(SFController\Controller $Controller, SFRouting\RoutedRequest $RoutedRequest) {
        $this->Mediator->triggerEvent(SFDispatcherEvents::BEFORE_CONTROLLER_INVOKED, $Controller);
        $actionName = $RoutedRequest->getAction();
        $parameters = $RoutedRequest->getParameters();
        \call_user_func_array(array($Controller, $actionName), $parameters);
        $this->Mediator->triggerEvent(SFDispatcherEvents::AFTER_CONTROLLER_INVOKED, $Controller);
    }

    /**
     * Gathers a 404 routed request from the SprayFire.Http.Routing.Router injected
     * at construction time and send off the appropriate response.
     */
    protected function dispatch404Response() {
        $RoutedRequest = $this->Router->get404RoutedRequest();
        if ($RoutedRequest->isStatic()) {
            $this->sendStaticRequest($RoutedRequest);
        } else {
            $this->sendDynamicRequest($RoutedRequest);
        }
    }

}