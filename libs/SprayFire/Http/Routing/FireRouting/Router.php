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
    \SprayFire\Http\Routing as SFRouting,
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
class Router extends SFCoreObject implements SFRouting\Router {

    /**
     * Ensures that the appropriate controller and action name are passed to the
     * SprayFire.Http.Routing.RoutedRequest.
     *
     * @property \SprayFire\Http\Routing\FireRouting\Normalizer
     */
    protected $Normalizer;

    /**
     * Holds the routes that we can match against SprayFire.Http.Request.
     *
     * @property \SprayFire\Http\Routing\RouteBag
     */
    protected $RouteBag;

    /**
     * Stores \SprayFire\Http\Routing\RoutedRequest objects against \SprayFire\Http\Request
     * objects used to create them.
     *
     * @property \SplObjectStorage
     */
    protected $RoutedRequestCache;

    /**
     * This string ensures that the appropriate install directory the framework
     * is in does not get included in routing.
     *
     * @property string
     */
    protected $installDir;

    /**
     * Please see the documentation on routing configurations at
     * https://github.com/cspray/SprayFire/wiki/HTTP-and-Routing
     *
     * @param \SprayFire\Http\Routing\FireRouting\RouteBag $RouteBag
     * @param \SprayFire\Http\Routing\FireRouting\Normalizer $Normalizer
     * @param string $installDir
     */
    public function __construct(RouteBag $RouteBag, Normalizer $Normalizer, $installDir = '') {
        $this->Normalizer = $Normalizer;
        $this->RouteBag = $RouteBag;
        $this->RoutedRequestCache = new SplObjectStorage();
        $this->installDir = (string) $installDir;
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

        $data = $this->getMatchedRouteAndParameters($Request);
        $Route = $data['Route'];
        $parameters = $data['parameters'];

        $RoutedRequest = new RoutedRequest(
            $Route->getControllerNamespace() . '.' . $this->normalizeController($Route->getControllerClass()),
            $this->normalizeAction($Route->getAction()),
            $parameters
        );
        $this->RoutedRequestCache[$Request] = $RoutedRequest;
        return $RoutedRequest;
    }

    /**
     * Will parse the given \SprayFire\Http\Request to determine if there is a
     * matched routed in the configuration, if there is not a 404 route will be
     * returned.
     *
     * @param \SprayFire\Http\Request $Request
     * @return array
     */
    protected function getMatchedRouteAndParameters(SFHttp\Request $Request) {
        $resourcePath = $this->cleanPath($Request->getUri()->getPath());
        $Route = $this->RouteBag->getRoute();
        $match = array();

        foreach ($this->RouteBag as $routePattern => $StoredRoute) {
            $routePattern = '#^' . $routePattern . '$#';
            if (\preg_match($routePattern, $resourcePath, $match)) {
                foreach ($match as $key => $val) {
                    // Ensures that numeric keys are removed from the match array
                    // only returning the appropriate named groups.
                    if ($key === (int) $key) {
                        unset($match[$key]);
                    }
                }
                $Route = $StoredRoute;
                break;
            }
        }

        return array(
            'Route' => $Route,
            'parameters' => $match
        );
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

    /**
     * Will remove the leading forward slash and the install directory from the
     * path requested.
     *
     * @param string $path
     * @return string
     */
    protected function cleanPath($path) {
        $path = $this->removeLeadingForwardSlash($path);
        $path = $this->removeInstallDirectory($path);
        $path = $this->removeLeadingForwardSlash($path);
        $path = $this->removeTrailingForwardSlash($path);
        $path = \strtolower($path);
        if (empty($path)) {
            return '/';
        }
        return '/' . $path . '/';
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function removeLeadingForwardSlash($uri) {
        $regex = '/^\//';
        $nothing = '';
        return \preg_replace($regex, $nothing, $uri);
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function removeTrailingForwardSlash($uri) {
        $regex = '/\/$/';
        $nothing = '';
        return \preg_replace($regex, $nothing, $uri);
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function removeInstallDirectory($uri) {
        $regex = '/^' . $this->installDir . '/';
        $nothing = '';
        return \preg_replace($regex, $nothing, $uri);
    }

}
