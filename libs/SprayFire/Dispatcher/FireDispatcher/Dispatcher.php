<?php

/**
 * The default dispatcher for the SprayFire framework.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Dispatcher\FireDispatcher;

use \SprayFire\Dispatcher\Dispatcher as DispatcherDispatcher,
    \SprayFire\Dispatcher\AppInitializer as DispatcherAppInitializer,
    \SprayFire\Service\Container as ServiceContainer,
    \SprayFire\Http\Routing\Router as HttpRouter,
    \SprayFire\Factory\Factory as Factory,
    \SprayFire\Http\Request as Request,
    \SprayFire\Mediator\Mediator as Mediator,
    \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\Controller\Controller as Controller,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\Mediator\DispatcherEvents as DispatcherEvents,
    \SprayFire\Exception\ResourceNotFound as ResourceNotFoundException;

class Dispatcher extends CoreObject implements DispatcherDispatcher {

    /**
     * @property SprayFire.Service.Container
     */
    protected $Container;

    /**
     * @property SprayFire.Http.Routing.Router
     */
    protected $Router;

    /**
     * @property SprayFire.Mediator.Mediator
     */
    protected $Mediator;

    /**
     * @property SprayFire.AppInitializer
     */
    protected $AppInitializer;

    /**
     * @property SprayFire.Factory.Factory
     */
    protected $ControllerFactory;

    /**
     * @property SprayFire.Factory.Factory
     */
    protected $ResponderFactory;

    /**
     * @property array
     */
    protected $environmentConfig;

    /**
     * @property boolean
     */
    protected $dispatching404;

    /**
     * @param SprayFire.Http.Routing.Router $Router
     * @param SprayFire.Mediator.Mediator $Mediator
     * @param SprayFire.Dispatcher.AppInitializer $AppInitializer
     * @param SprayFire.Factory.Factory $Factory
     * @param SprayFire.Factory.Factory $Factory
     */
    public function __construct(HttpRouter $Router, Mediator $Mediator, DispatcherAppInitializer $AppInitializer, Factory $ControllerFactory, Factory $ResponderFactory) {
        $this->Router = $Router;
        $this->Mediator = $Mediator;
        $this->AppInitializer = $AppInitializer;
        $this->ControllerFactory = $ControllerFactory;
        $this->ResponderFactory = $ResponderFactory;
        $this->dispatching404 = false;
    }

    /**
     * @param SprayFire.Http.Request $Request
     */
    public function dispatchResponse(Request $Request) {
        $this->Mediator->triggerEvent(DispatcherEvents::BEFORE_ROUTING, $Request);
        $RoutedRequest = $this->Router->getRoutedRequest($Request);
        if ($RoutedRequest->isStatic()) {
            $this->sendStaticRequest($RoutedRequest);
        } else {
            $this->AppInitializer->initializeApp($RoutedRequest);
            $this->sendDynamicRequest($RoutedRequest);
        }
    }

    /**
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     */
    protected function sendStaticRequest(RoutedRequest $RoutedRequest) {
        $staticFiles = $this->Router->getStaticFilePaths($RoutedRequest);
        $Responder = $this->ResponderFactory->makeObject($staticFiles['responderName']);
        echo $Responder->generateStaticResponse($staticFiles['layoutPath'], $staticFiles['templatePath']);
    }

    protected function sendDynamicRequest(RoutedRequest $RoutedRequest) {
        try {
            $controllerName = $RoutedRequest->getController();
            $actionName = $RoutedRequest->getAction();
            $Controller = $this->generateController($controllerName, $actionName);
            $this->invokeController($Controller, $RoutedRequest);
            $ResponderName = $Controller->getResponderName();
            $Responder = $this->ResponderFactory->makeObject($ResponderName);
            echo $Responder->generateDynamicResponse($Controller);
        } catch(ResourceNotFoundException $TypeNotFoundExc) {
            $this->dispatch404Response();
        }
    }

    protected function generateController($controllerName, $actionName) {
        $Controller = $this->ControllerFactory->makeObject($controllerName);
        if (!\method_exists($Controller, $actionName)) {
            throw new ResourceNotFoundException('The given object ' . $controllerName . ' does not have the requested action ' . $actionName);
        }
        return $Controller;
    }

    /**
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return SprayFire.Controller.Controller
     */
    protected function invokeController(Controller $Controller, RoutedRequest $RoutedRequest) {
        $actionName = $RoutedRequest->getAction();
        $parameters = $RoutedRequest->getParameters();
        \call_user_func_array(array($Controller, $actionName), $parameters);
    }

    protected function dispatch404Response() {
        if ($this->dispatching404 === true) {
            throw new \SprayFire\Exception\FatalRuntimeException('An infinite loop from a missing 404 file was found.');
        }
        $RoutedRequest = $this->Router->get404RoutedRequest();
        if ($RoutedRequest->isStatic()) {
            $this->sendStaticRequest($RoutedRequest);
        } else {
            $this->sendDynamicRequest($RoutedRequest);
        }
    }

}