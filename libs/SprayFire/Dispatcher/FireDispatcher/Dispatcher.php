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
    \SprayFire\Http\Routing\Router as Router,
    \SprayFire\Service\Container as ServiceContainer,
    \SprayFire\Factory\Factory as Factory,
    \SprayFire\Http\Request as Request,
    \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\CoreObject as CoreObject;

class Dispatcher extends CoreObject implements DispatcherDispatcher {

    /**
     * @property SprayFire.Http.Routing.Router
     */
    protected $Router;

    protected $Container;

    /**
     * @property SprayFire.Logging.LogOverseer
     */
    protected $LogOverseer;

    /**
     * This should be a SprayFire.Controller.Factory object
     *
     * @protected SprayFire.Factory.Factory
     */
    protected $ControllerFactory;

    /**
     * This should be a SprayFire.Responder.Factory object
     *
     * @protected SprayFire.Factory.Factory
     */
    protected $ResponderFactory;

    /**
     *
     * @param SprayFire.Http.Routing.Router $Router
     * @param SprayFire.Service.Container $ServiceContainer
     * @param SprayFire.Logging.LogOverseer $LogOverseer
     * @param SprayFire.Factory.Factory $ControllerFactory
     * @param SprayFire.Factory.Factory $ResponderFactory
     */
    public function __construct(Router $Router, ServiceContainer $Container, LogOverseer $LogOverseer, Factory $ControllerFactory, Factory $ResponderFactory) {
        $this->Router = $Router;
        $this->Container = $Container;
        $this->LogOverseer = $LogOverseer;
        $this->ControllerFactory = $ControllerFactory;
        $this->ResponderFactory = $ResponderFactory;
    }

    /**
     * @param SprayFire.Http.Request $Request
     */
    public function dispatchResponse(Request $Request) {
        $RoutedRequest = $this->Router->getRoutedRequest($Request);
        $this->Container->addService($RoutedRequest);
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
            $this->LogOverseer->logError($errorMessage);
            $Controller->giveDirtyData(\compact('errorMessage'));
            $Controller->$action();
        }
        return $Controller;
    }

}