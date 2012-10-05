<?php

/**
 * Implementation of SprayFire.Http.Routing.Router provided by the default SprayFire
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
    \SprayFire\CoreObject as SFCoreObject;

/**
 * At the moment this implementation is a configuration strict implementation,
 * meaning that if the route requested is not in the configuration passed to the
 * router then a SprayFire.Http.Routing.RoutedRequest representing a 404 response
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
     * @property SprayFire.Http.Routing.FireRouting.Normalizer
     */
    protected $Normalizer;

    /**
     *
     * @property SprayFire.Http.Routing.FireRouting.RouteBag
     */
    protected $RouteBag;

    /**
     * Stores SprayFire.Http.Routing.RoutedRequest objects against SprayFire.Http.Request
     * objects used to create them.
     *
     * @property SplObjectStorage
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
     * An array of data used as a value fallback in case a routing configuration
     * is not properly provided for the route.
     *
     * @property array
     */
    protected $defaultsFallbackMap;

    /**
     * Default values for dynamic requests that were provided by the routing
     * configuration.
     *
     * @property array
     */
    protected $defaults;

    /**
     * Default values for static requests that were provided by the routing
     * configuration.
     *
     * @property array
     */
    protected $staticDefaults;

    /**
     * A routing configuration that represents a 404 route response.
     *
     * @property array
     */
    protected $noResourceConfiguration;

    /**
     * Please see the documentation on routing configurations at
     * https://github.com/cspray/SprayFire/wiki/HTTP-and-Routing
     *
     * @param SprayFire.Http.Routing.FireRouting.Normalizer
     * @param array $config
     * @param string $installDir
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    public function __construct(RouteBag $RouteBag, Normalizer $Normalizer, $installDir = '') {
        $this->Normalizer = $Normalizer;
        $this->RouteBag = $RouteBag;
        $this->RoutedRequestCache = new \SplObjectStorage();
        $this->installDir = (string) $installDir;
    }

    /**
     * Creates an array of default value fallbacks to be used if the route configuration
     * passed did not include default values.
     *
     * @return array
     */
    protected function createDefaultsFallbackMap() {
        $defaultsFallbackMap = array();
        $defaultsFallbackMap['defaults'] = array(
            'namespace' => SFRouting\ConfigFallbacks::DEFAULT_NAMESPACE,
            'controller' => SFRouting\ConfigFallbacks::DEFAULT_CONTROLLER,
            'action' => SFRouting\ConfigFallbacks::DEFAULT_ACTION,
            'parameters' => array(),
            'method' => SFRouting\ConfigFallbacks::DEFAULT_METHOD
        );
        $defaultsFallbackMap['404'] = array();
        $defaultsFallbackMap['500'] = array();
        $defaultsFallbackMap['routes'] = array();
        return $defaultsFallbackMap;
    }

    /**
     * Creates an appropriate array of default configuration values, using defaults
     * provided by the routing configuration and if not provided reverting back
     * the default fallbacks.
     *
     * @param array $config
     * @param string $property
     * @return array
     */
    protected function getDefaultConfig(array $config, $property) {
        $defaults = array();
        if (isset($config[$property]) && \is_array($config[$property])) {
            $defaults = $config[$property];
        }

        $defaultsFallback = $this->defaultsFallbackMap[$property];
        foreach ($defaultsFallback as $key => $value) {
            if (!isset($defaults[$key])) {
                $defaults[$key] = $value;
            }
        }
        return $defaults;
    }

    /**
     * Bsed on the URI path and HTTP method passed in the given SprayFire.Http.Request
     * will return an appropriate SprayFire.Http.Routing.FireRouting.RoutedRequest
     * configured for the appropriate resource.
     *
     * @param SprayFire.Http.Request $Request
     * @return SprayFire.Http.Routing.FireRouting.RoutedRequest
     */
    public function getRoutedRequest(SFHttp\Request $Request) {
        if (isset($this->RoutedRequestCache[$Request])) {
            return $this->RoutedRequestCache[$Request];
        }

        $data = $this->getMatchedRouteOr404($Request);
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
     * Will parse the given SprayFire.Http.Request to determine if there is a
     * matched routed in the configuration, if there is not a 404 route will be
     * returned.
     *
     * @param SprayFire.Http.Request $Request
     * @return array
     */
    protected function getMatchedRouteOr404(SFHttp\Request $Request) {
        $resourcePath = $this->cleanPath($Request->getUri()->getPath());
        $requestMethod = \strtolower($Request->getMethod());
        $returnRoute = $this->noResourceConfiguration;
        $match = array();

        foreach ($this->RouteBag as $routePattern => $Route) {
            $routePattern = '#^' . $routePattern . '$#';
            if (\preg_match($routePattern, $resourcePath, $match)) {
                foreach ($match as $key => $val) {
                    // Ensures that numeric keys are removed from the match array
                    // only returning the appropriate named groups.
                    if ($key === (int) $key) {
                        unset($match[$key]);
                    }
                }
                $returnRoute = $Route;
            }
        }

        return array(
            'Route' => $returnRoute,
            'parameters' => $match
        );
    }

    /**
     * Will return a RoutedRequest object that marries back to the 404 configuration
     * provided.
     *
     * Note that we are creating new objects here so that we can properly ensure
     * that new 404 configurations passed at runtime are honored.  If we were to
     * attempt a simple cache of the object created then only the configuration
     * set at the time of method invocation would be honored.  We could attempt
     * to serialize and hash each configuration that is used but that is far too
     * much overhead for a method that should only be called sparingly.
     *
     * @return SprayFire.Http.Routing.FireRouting.RoutedRequest
     */
    public function get404RoutedRequest() {
        $route = $this->normalizeRoute($this->noResourceConfiguration);
        $RoutedRequest = new RoutedRequest(
            $route['controller'],
            $route['action'],
            $route['parameters'],
            $route['isStatic']
        );
        if ($route['isStatic']) {
            $this->StaticFilesStorage[$RoutedRequest] = $this->noResourceConfiguration;
        }
        return $RoutedRequest;
    }

    /**
     * Allows the setting of 404 configuration at runtime, after the Router
     * implementation has been created.
     *
     * @param array $configuration
     */
    public function set404Configuration(array $configuration) {
        $this->noResourceConfiguration = $configuration;
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
     * @param $uri string
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

    /**
     * @param string $routeKey
     * @return boolean
     */
    protected function checkRequestIsStatic($routeKey) {
        if ($routeKey !== false && isset($this->config['routes'][$routeKey]['static'])) {
            return true;
        }
        return false;
    }

}