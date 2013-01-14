<?php

/**
 * Implementation of \SprayFire\Http\Routing\Router provided by the default SprayFire
 * install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http as SFHttp,
    \SprayFire\Http\Routing as SFHttpRouting,
    \SprayFire\CoreObject as SFCoreObject,
    \SplObjectStorage as SplObjectStorage;

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
 * @subpackage Http.Routing.FireRouting
 *
 * @todo
 * We need to take a look at implementing a series of strategies for this implementation
 * that allows a user to set the Router to be one of three processing types:
 *
 * - Configuration only
 * - Convention only
 * - Configuration, fallback to Convention
 */
class Router extends SFCoreObject implements SFHttpRouting\Router {

    /**
     * @property \SprayFire\Http\Routing\MatchStrategy
     */
    protected $MatchStrategy;

    /**
     * Holds the routes that we can match against SprayFire.Http.Request.
     *
     * @property \SprayFire\Http\Routing\RouteBag
     */
    protected $RouteBag;

    /**
     * Ensures that the appropriate controller and action name are passed to the
     * SprayFire.Http.Routing.RoutedRequest.
     *
     * @property \SprayFire\Http\Routing\FireRouting\Normalizer
     */
    protected $Normalizer;

    /**
     * Stores \SprayFire\Http\Routing\RoutedRequest objects against \SprayFire\Http\Request
     * objects used to create them.
     *
     * @property \SplObjectStorage
     */
    protected $RoutedRequestCache;

    /**
     * Please see the documentation on routing configurations at
     * https://github.com/cspray/SprayFire/wiki/HTTP-and-Routing
     *
     * @param \SprayFire\Http\Routing\MatchStrategy $Strategy
     * @param \SprayFire\Http\Routing\RouteBag $RouteBag
     * @param \SprayFire\Http\Routing\FireRouting\Normalizer $Normalizer
     */
    public function __construct(SFHttpRouting\MatchStrategy $Strategy, SFHttpRouting\RouteBag $RouteBag, Normalizer $Normalizer) {
        $this->MatchStrategy = $Strategy;
        $this->RouteBag = $RouteBag;
        $this->Normalizer = $Normalizer;
        $this->RoutedRequestCache = new SplObjectStorage();
    }

    /**
     * Based on the URI path and HTTP method passed in the given \SprayFire\Http\Request
     * will return an appropriate \SprayFire\Http\Routing\FireRouting\RoutedRequest
     * configured for the appropriate resource.
     *
     * @param \SprayFire\Http\Request $Request
     * @return \SprayFire\Http\Routing\FireRouting\RoutedRequest
     */
    public function getRoutedRequest(SFHttp\Request $Request) {
        if (isset($this->RoutedRequestCache[$Request])) {
            return $this->RoutedRequestCache[$Request];
        }

        $data = $this->MatchStrategy->getRouteAndParameters($this->RouteBag, $Request);
        /* @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[SFHttpRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[SFHttpRouting\MatchStrategy::PARAMETER_KEY];

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
