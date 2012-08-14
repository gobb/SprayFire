<?php

/**
 * Base class to route an HTTP request to the appropriate controller, action and
 * parameters.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http\Routing\Router as HttpRoutingRouter,
    \SprayFire\Http\Routing\RoutedRequest as HttpRoutingRoutedRequest,
    \SprayFire\Http\Request as HttpRequest,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\Http\Routing\FireRouting\Normalizer as Normalizer,
    \SprayFire\Http\Routing\ConfigFallbacks as ConfigFallbacks,
    \SprayFire\Exception\FatalRuntimeException as FatalRunTimeException;

class Router extends CoreObject implements HttpRoutingRouter {

    /**
     * @property SprayFire.Http.Routing.Normalizer
     */
    protected $Normalizer;

    /**
     * @property SplObjectStorage
     */
    protected $StaticFilesStorage;

    /**
     * @property SplObjectStorage
     */
    protected $RoutedRequestCache;

    /**
     * @property string
     */
    protected $installDir;

    /**
     * @property array
     */
    protected $routes;

    /**
     * @property array
     */
    protected $defaultsFallbackMap;

    /**
     * @property array
     */
    protected $defaults;

    /**
     * @property array
     */
    protected $staticDefaults;

    /**
     * @property array
     */
    protected $noResource;

    /**
     * @param SprayFire.Config.Routes $Config
     * @param SprayFire.Http.Routing.FireRouting.Normalizer $Normalizer
     * @param SprayFire.FileSys.PathGenerator
     * @param string $configPath
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
        $this->noResource = $this->getDefaultConfig($config, '404');
        $this->routes = $this->getDefaultConfig($config, 'routes');
    }

    /**
     * Creates an array of default configuration values used if the configuration
     * passed does not have the appropriate configuration set.
     *
     * @return array
     */
    protected function createDefaultsFallbackMap() {
        $defaultsFallbackMap = array();
        $defaultsFallbackMap['defaults'] = array(
            'namespace' => ConfigFallbacks::DEFAULT_NAMESPACE,
            'controller' => ConfigFallbacks::DEFAULT_CONTROLLER,
            'action' => ConfigFallbacks::DEFAULT_ACTION,
            'parameters' => array(),
            'method' => ConfigFallbacks::DEFAULT_METHOD
        );
        $defaultsFallbackMap['staticDefaults'] = array(
            'static' => ConfigFallbacks::DEFAULT_STATIC,
            'responderName' => ConfigFallbacks::DEFAULT_STATIC_RESPONDER_NAME,
            'layoutPath' => ConfigFallbacks::DEFAULT_STATIC_LAYOUT_PATH,
            'templatePath' => ConfigFallbacks::DEFAULT_STATIC_TEMPLATE_PATH,
            'method' => ConfigFallbacks::DEFAULT_METHOD
        );
        $defaultsFallbackMap['404'] = array();
        $defaultsFallbackMap['500'] = array();
        $defaultsFallbackMap['routes'] = array();
        return $defaultsFallbackMap;
    }

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
     *
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return type
     */
    public function getStaticFilePaths(HttpRoutingRoutedRequest $RoutedRequest) {
        if (isset($this->StaticFilesStorage[$RoutedRequest])) {
            return $this->StaticFilesStorage[$RoutedRequest];
        }
        return array('responderName' => '', 'layoutPath' => '', 'templatePath' => '');
    }

    /**
     * Will create a RoutedRequest appropriate to the passed Request object
     *
     * @param SprayFire.Http.Request $Request
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function getRoutedRequest(HttpRequest $Request) {
        if (isset($this->RoutedRequestCache[$Request])) {
            return $this->RoutedRequestCache[$Request];
        }

        $resourcePath = $this->cleanPath($Request->getUri()->getPath());
        $requestMethod = \strtolower($Request->getMethod());
        $route = $this->noResource;

        foreach ($this->routes as $routePattern => $routeConfig) {
            $routePattern = '#^' . $routePattern . '$#';
            if (\preg_match($routePattern, $resourcePath, $match)) {
                $route = $routeConfig;
                $routeMethod = $this->getRouteMethod($route);
                if ($requestMethod !== $routeMethod) {
                    $route = $this->noResource;
                    break;
                }

                if (!isset($route['parameters'])) {
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

        $RoutedRequest = new RoutedRequest($controller, $action, $parameters, $isStatic);
        $this->RoutedRequestCache[$Request] = $RoutedRequest;
        if ($isStatic) {
            $this->StaticFilesStorage[$RoutedRequest] = $route;
        }

        return $RoutedRequest;
    }

    public function get404RoutedRequest() {
        return new RoutedRequest('', '', array());
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

    /**
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @param string $routeKey
     */
    protected function storeStaticFilePaths(RoutedRequest $RoutedRequest, $routeKey) {
        $layoutPath = $this->config['routes'][$routeKey]['static']['layoutPath'];
        $templatePath = $this->config['routes'][$routeKey]['static']['templatePath'];

        $data = array(
            'layout' => $layoutPath,
            'template' => $templatePath
        );
        $this->StaticFilesStorage->attach($RoutedRequest, $data);
    }

}