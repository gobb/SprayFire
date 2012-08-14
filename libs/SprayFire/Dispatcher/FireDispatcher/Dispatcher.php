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
    \SprayFire\Factory\Factory as Factory,
    \SprayFire\Http\Request as Request,
    \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\CoreObject as CoreObject;

class Dispatcher extends CoreObject implements DispatcherDispatcher {

    /**
     *
     * @property SprayFire.Service.Container
     */
    protected $Container;

    /**
     *
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
    public function __construct(ServiceContainer $Container, array $environmentConfig) {
        $this->Container = $Container;
        $this->environmentConfig = $environmentConfig;
        $this->setContainerDependencies();
    }

    protected function setContainerDependencies() {
        $this->Router = $this->Container->getService($this->environmentConfig['services']['HttpRouter']['name']);
        $this->LogOverseer = $this->Container->getService($this->environmentConfig['services']['Logging']['name']);
        $this->ControllerFactory = $this->Container->getService($this->environmentConfig['services']['ControllerFactory']['name']);
        $this->ResponderFactory = $this->Container->getService($this->environmentConfig['services']['ResponderFactory']['name']);
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