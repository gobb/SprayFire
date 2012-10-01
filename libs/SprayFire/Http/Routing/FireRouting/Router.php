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
     * Stores arrays of configuration data against a created
     * SprayFire.Http.Routing.RoutedRequest.
     *
     * @property SplObjectStorage
     */
    protected $StaticFilesStorage;

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
     * An array of route matching regular expressions and the routing configuration
     * data associated with that expression.
     *
     * @property array
     */
    protected $routes;

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
    public function __construct(Normalizer $Normalizer, array $config, $installDir = '') {
        $this->Normalizer = $Normalizer;
        $this->config = $config;
        $this->StaticFilesStorage = new \SplObjectStorage();
        $this->RoutedRequestCache = new \SplObjectStorage();
        $this->installDir = (string) $installDir;
        $this->defaultsFallbackMap = $this->createDefaultsFallbackMap();
        $this->defaults = $this->getDefaultConfig($config, 'defaults');
        $this->staticDefaults = $this->getDefaultConfig($config, 'staticDefaults');
        $this->noResourceConfiguration = $this->getDefaultConfig($config, '404');
        $this->routes = $this->getDefaultConfig($config, 'routes');
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
        $defaultsFallbackMap['staticDefaults'] = array(
            'static' => SFRouting\ConfigFallbacks::DEFAULT_STATIC,
            'responderName' => SFRouting\ConfigFallbacks::DEFAULT_STATIC_RESPONDER_NAME,
            'layoutPath' => SFRouting\ConfigFallbacks::DEFAULT_STATIC_LAYOUT_PATH,
            'templatePath' => SFRouting\ConfigFallbacks::DEFAULT_STATIC_TEMPLATE_PATH,
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
     * Returns an array of information about the static file paths, if they exist
     * for a given $RoutedRequest.
     *
     * This method will always return an array with at least the following keys:
     *
     * array(
     *      'responderName' => '',
     *      'layoutPath'    => '',
     *      'templatePath'  => ''
     * )
     *
     * If there are static files found for the given $RoutedRequest then the keys
     * will be filled in with the appropriate information provided by the configuration.
     * If there are no static files found for the given $RoutedRequest then the
     * keys will have blank strings as values.
     *
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return type
     *
     * @todo
     * Make sure that a test covers the retrieval of static files for a $RoutedRequest
     * that has no static files.  We may need to look at changing the empty string
     * on a bad request.  Perhaps we should even look at just returning an empty
     * array or false.
     *
     */
    public function getStaticFilePaths(SFRouting\RoutedRequest $RoutedRequest) {
        if (isset($this->StaticFilesStorage[$RoutedRequest])) {
            return $this->StaticFilesStorage[$RoutedRequest];
        }
        return array('responderName' => '', 'layoutPath' => '', 'templatePath' => '');
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

        $route = $this->getMatchedRouteOr404($Request);
        $normalizedRoute = $this->normalizeRoute($route);

        $RoutedRequest = new RoutedRequest(
            $normalizedRoute['controller'],
            $normalizedRoute['action'],
            $normalizedRoute['parameters'],
            $normalizedRoute['isStatic']
        );
        $this->RoutedRequestCache[$Request] = $RoutedRequest;
        if ($normalizedRoute['isStatic']) {
            $this->StaticFilesStorage[$RoutedRequest] = $route;
        }

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
        $route = $this->noResourceConfiguration;

        foreach ($this->routes as $routePattern => $routeConfig) {
            $routePattern = '#^' . $routePattern . '$#';
            if (\preg_match($routePattern, $resourcePath, $match)) {
                $route = $routeConfig;
                $routeMethod = $this->getRouteMethod($route);
                if ($requestMethod !== $routeMethod) {
                    $route = $this->noResourceConfiguration;
                    break;
                }

                if (!isset($route['parameters'])) {
                    // this is here to remove numeric indexes from the matched
                    // regex so that we only get regex capturing groups
                    foreach ($match as $key => $val) {
                        if ($key === (int) $key) {
                            unset($match[$key]);
                        }
                    }
                    $route['parameters'] = $match;
                }
                break;
            }
        }

        return $route;
    }

    /**
     * Will take a $route configuration and return an array with keys matching to
     * the parameters passed to the created SprayFire.Http.Routing.FireRouting.RoutedRequest.
     *
     * @param array $route
     * @return array
     *
     * @todo
     * Come up with a better name for this function, it does not adequately describe
     * what the functionality is doing.
     */
    protected function normalizeRoute(array $route) {
        if (isset($route['static']) && $route['static'] === true) {
            $this->setStaticDefaults($route);
            $controller = '';
            $action = '';
            $parameters = array();
            $isStatic = true;
        } else {
            $this->setDefaults($route);
            $controller = $route['namespace'] . '.' . $this->normalizeController($route['controller']);
            $action = $this->normalizeAction($route['action']);
            $parameters = $route['parameters'];
            $isStatic = false;
        }
        return array(
            'controller' => $controller,
            'action' => $action,
            'parameters' => $parameters,
            'isStatic' => $isStatic
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
     * Will return the method for the given route, or the default method if none
     * was provided.
     *
     * @param array $route
     * @return string
     */
    protected function getRouteMethod(array $route) {
        if (isset($route['method'])) {
            return \strtolower($route['method']);
        }
        return \strtolower($this->defaults['method']);
    }

    /**
     * Replace any values that are not set properly in the route with the route
     * static defaults.
     *
     * @param array &$route
     */
    protected function setStaticDefaults(array &$route) {
        foreach ($this->staticDefaults as $key => $defaultValue) {
            if (!isset($route[$key]) || empty($route[$key])) {
                $route[$key] = $defaultValue;
            }
        }
    }

    /**
     * Replace any values that are not set properly in the route with the route
     * defaults.
     *
     * @param array &$route
     */
    protected function setDefaults(array &$route) {
        foreach ($this->defaults as $key => $defaultValue) {
            if (!isset($route[$key]) || empty($route[$key])) {
                $route[$key] = $defaultValue;
            }
        }
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