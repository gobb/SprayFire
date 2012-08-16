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
    \SprayFire\Service\Container as ServiceContainer,
    \SprayFire\Http\Routing\Router as HttpRouter,
    \SprayFire\Factory\Factory as Factory,
    \SprayFire\Http\Request as Request,
    \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\CoreObject as CoreObject;

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
     * @property SprayFire.Logging.LogOverseer
     */
    protected $LogOverseer;

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
     * @param SprayFire.Service.Container $ServiceContainer
     * @param array $environmentConfig
     */
    public function __construct(HttpRouter $Router, Factory $ControllerFactory, Factory $ResponderFactory) {
        $this->Router = $Router;
        $this->ControllerFactory = $ControllerFactory;
        $this->ResponderFactory = $ResponderFactory;
    }

    /**
     * @param SprayFire.Http.Request $Request
     */
    public function dispatchResponse(Request $Request) {
        $RoutedRequest = $this->Router->getRoutedRequest($Request);
        if ($RoutedRequest->isStatic()) {
            $staticFiles = $this->Router->getStaticFilePaths($RoutedRequest);
            $Responder = $this->ResponderFactory->makeObject($staticFiles['responderName']);
            echo $Responder->generateStaticResponse($staticFiles['layoutPath'], $staticFiles['templatePath']);
        } else {
            $Controller = $this->invokeController($RoutedRequest);
            $Responder = $this->ResponderFactory->makeObject($Controller->getResponderName());
            echo $Responder->generateDynamicResponse($Controller);
        }

    }

    /**
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return SprayFire.Controller.Controller
     */
    protected function invokeController(RoutedRequest $RoutedRequest) {
        $controllerName = $RoutedRequest->getController();
        $Controller = $this->ControllerFactory->makeObject($controllerName);
        $action = $RoutedRequest->getAction();
        if (\method_exists($Controller, $action)) {
            $Controller->$action();
        } else {
            $nullController = $this->ControllerFactory->getNullObjectType();
            $Controller = $this->ControllerFactory->makeObject($nullController);
            $errorMessage = 'The action, ' . $action . ', was not found in, ' . $controllerName . '.';
            $Controller->giveDirtyData(\compact('errorMessage'));
            $Controller->$action();
        }
        return $Controller;
    }

}