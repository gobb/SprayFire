<?php

/**
 * The default dispatcher for the SprayFire framework.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Dispatcher\Dispatcher as Dispatcher,
    \SprayFire\Http\Request as Request,
    \SprayFire\Http\Routing\Router as Router,
    \SprayFire\Factory\Factory as Factory,
    \SprayFire\CoreObject as CoreObject;

class FireDispatcher extends CoreObject implements Dispatcher {

    /**
     * @property SprayFire.Http.Routing.Router
     */
    protected $Router;

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
     * @param SprayFire.Factory.Factory $ControllerFactory
     * @param SprayFire.Factory.Factory $ResponderFactory
     */
    public function __construct(Router $Router, Factory $ControllerFactory, Factory $ResponderFactory) {
        $this->Router = $Router;
        $this->ControllerFactory = $ControllerFactory;
        $this->ResponderFactory = $ResponderFactory;
    }

    /**
     * @param SprayFire.Http.Request $Request
     */
    public function dispatchResponse(Request $Request) {
        $RoutedRequest = $this->Router->getRoutedRequest($Request);
        $controllerName = $RoutedRequest->getController();
        $Controller = $this->ControllerFactory->makeObject($controllerName);
        $action = $RoutedRequest->getAction();
        $Controller->$action();
        $Responder = $this->ResponderFactory->makeObject($Controller->getResponderName());
        echo $Responder->generateDynamicResponse($Controller);
    }

}