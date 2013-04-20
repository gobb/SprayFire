<?php

/**
 * Implementation of \SprayFire\Http\Routing\Router provided by the default SprayFire
 * install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Routing\FireRouting;

use \SprayFire\Http,
    \SprayFire\Routing,
    \SprayFire\StdLib,
    \SplObjectStorage;

/**
 * At the moment this implementation is a configuration strict implementation,
 * meaning that if the route requested is not in the configuration passed to the
 * router then a \SprayFire\Http\Routing\RoutedRequest representing a 404 response
 * will be returned.
 *
 * The routing configuration passed is expected to be of a specific format that
 * is detailed at https://github.com/cspray/SprayFire/wiki/HTTP-and-Routing.
 *
 * @package SprayFire
 * @subpackage Routing.Implementation
 */
class Router extends StdLib\CoreObject implements Routing\Router {

    /**
     * @property \SprayFire\Routing\MatchStrategy
     */
    protected $MatchStrategy;

    /**
     * Holds the routes that we can match against \SprayFire\Http\Request.
     *
     * @property \SprayFire\Routing\RouteBag
     */
    protected $RouteBag;

    /**
     * Ensures that the appropriate controller and action name are passed to the
     * SprayFire.Http.Routing.RoutedRequest.
     *
     * @property \SprayFire\Routing\FireRouting\Normalizer
     */
    protected $Normalizer;

    /**
     * Stores \SprayFire\Routing\RoutedRequest objects against \SprayFire\Http\Request
     * objects used to create them.
     *
     * @property \SplObjectStorage
     */
    protected $RoutedRequestCache;

    /**
     * Please see the documentation on routing configurations at
     * https://github.com/cspray/SprayFire/wiki/HTTP-and-Routing
     *
     * @param \SprayFire\Routing\MatchStrategy $Strategy
     * @param \SprayFire\Routing\RouteBag $RouteBag
     * @param \SprayFire\Routing\FireRouting\Normalizer $Normalizer
     */
    public function __construct(Routing\MatchStrategy $Strategy, Routing\RouteBag $RouteBag, Normalizer $Normalizer) {
        $this->MatchStrategy = $Strategy;
        $this->RouteBag = $RouteBag;
        $this->Normalizer = $Normalizer;
        $this->RoutedRequestCache = new SplObjectStorage();
    }

    /**
     * Based on the URI path and HTTP method passed in the given \SprayFire\Http\Request
     * will return an appropriate \SprayFire\Routing\FireRouting\RoutedRequest
     * configured for the appropriate resource.
     *
     * @param \SprayFire\Http\Request $Request
     * @return \SprayFire\Routing\FireRouting\RoutedRequest
     */
    public function getRoutedRequest(Http\Request $Request) {
        if (isset($this->RoutedRequestCache[$Request])) {
            return $this->RoutedRequestCache[$Request];
        }

        $data = $this->MatchStrategy->getRouteAndParameters($this->RouteBag, $Request);
        /* @var \SprayFire\Routing\Route $Route */
        $Route = $data[Routing\MatchStrategy::ROUTE_KEY];
        $parameters = $data[Routing\MatchStrategy::PARAMETER_KEY];

        $RoutedRequest = new RoutedRequest(
            $Route->getControllerNamespace() . '.' . $this->normalizeController($Route->getControllerClass()),
            $this->normalizeAction($Route->getAction()),
            $parameters
        );
        $this->RoutedRequestCache[$Request] = $RoutedRequest;
        return $RoutedRequest;
    }

    /**
     * Will normalize the controller if any non-alphanumeric characters are found
     *
     * @param string $controller
     * @return string
     */
    protected function normalizeController($controller) {
        if (\preg_match('/[^A-Za-z0-9]/', $controller)) {
            return $this->Normalizer->normalizeController($controller);
        } else {
            return $controller;
        }
    }

    /**
     * Will normalize the action if any non-alphanumeric characters are found
     *
     * @param string $action
     * @return string
     */
    protected function normalizeAction($action) {
        if (\preg_match('/[^A-Za-z0-9]/', $action)) {
            return $this->Normalizer->normalizeAction($action);
        } else {
            return $action;
        }
    }

}
