<?php

/**
 * Base class to route an HTTP request to the appropriate controller, action and
 * parameters.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing;

use \SprayFire\Http\Routing\Router as Router,
    \SprayFire\Http\Request as Request,
    \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\FileSys\PathGenerator as PathGenerator,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\Http\Routing\Normalizer as Normalizer,
    \SprayFire\Exception\FatalRuntimeException as FatalRunTimeException;

class StandardRouter extends CoreObject implements Router {

    /**
     * @property SprayFire.Http.Routing.Normalizer
     */
    protected $Normalizer;

    /**
     * @property SprayFire.FileSys.PathGenerator
     */
    protected $Paths;

    /**
     * @property SplObjectStorage
     */
    protected $StaticFilesStorage;

    /**
     * @property SplObjectStorage
     */
    protected $RoutedRequestCache;

    /**
     * @property array
     */
    protected $config;

    /**
     * @property string
     */
    protected $installDir;

    /**
     * @property string
     */
    protected $defaultController;

    /**
     * @property string
     */
    protected $defaultAction;

    /**
     * @property array
     */
    protected $defaultParameters;

    /**
     * @property string
     */
    protected $defaultNamespace;

    /**
     * @param SprayFire.Http.Routing.Normalizer $Normalizer
     * @param string $configPath
     * @param string $installDir
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    public function __construct(Normalizer $Normalizer, PathGenerator $Paths, $configPath, $installDir = '') {
        $this->Normalizer = $Normalizer;
        $this->Paths = $Paths;
        $this->StaticFilesStorage = new \SplObjectStorage();
        $this->RoutedRequestCache = new \SplObjectStorage();
        $this->config = $this->generateConfig($configPath);
        $this->installDir = (string) $installDir;
        $this->defaultController = (string) $this->config['defaults']['controller'];
        $this->defaultAction = (string) $this->config['defaults']['action'];
        $this->defaultParameters = (array) $this->config['defaults']['parameters'];
        $this->defaultNamespace = (string) $this->config['defaults']['namespace'];

    }

    /**
     * @param string $configPath
     * @return array
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    protected function generateConfig($configPath) {
        $configPath = (string) $configPath;
        $contents = \file_get_contents($configPath);
        if ($contents === false) {
            throw new FatalRuntimeException('The configuration for HTTP routing could not be found.');
        }
        return \json_decode($contents, true);
    }

    public function getStaticFilePaths(RoutedRequest $RoutedRequest) {
        if (isset($this->StaticFilesStorage[$RoutedRequest])) {
            return $this->StaticFilesStorage[$RoutedRequest];
        }
        return array('layout' => '', 'template' => '');
    }

    /**
     * Will create a RoutedRequest appropriate to the passed Request object
     *
     * @param SprayFire.Http.Request $Request
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function getRoutedRequest(Request $Request) {
        if (isset($this->RoutedRequestCache[$Request])) {
            return $this->RoutedRequestCache[$Request];
        }
        $Uri = $Request->getUri();
        $path = $Uri->getPath();
        $cleanPath = $this->cleanPath($path);
        $fragments = $this->parseFragments($cleanPath);
        $route = $this->routeRequestedFragments($fragments);
        $controller = $route['controller'];
        $action = $route['action'];
        $parameters = $route['parameters'];
        $isStatic = $route['isStatic'];
        $RoutedRequest = new \SprayFire\Http\Routing\StandardRoutedRequest($controller, $action, $parameters, $isStatic);
        if ($isStatic) {
            $this->storeStaticFilePaths($RoutedRequest, $route['key']);
        }
        $this->RoutedRequestCache[$Request] = $RoutedRequest;
        return $RoutedRequest;

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

        return $path;
    }

    /**
     * Creates an associative array with the keys 'controller', 'action' and 'parameters'
     * as parsed from the path.
     *
     * @param string $cleanPath
     * @return array
     */
    protected function parseFragments($cleanPath) {
        $parsedUri = \explode('/', $cleanPath);
        $controller = \trim(\array_shift($parsedUri));
        if (empty($controller)) {
            $controller = $this->defaultController;
            $action = $this->defaultAction;
            $parameters = $this->defaultParameters;
        } else {
            if ($this->isMarkedParameter($controller)) {
                \array_unshift($parsedUri, $controller);
                $controller = $this->defaultController;
                $action = $this->defaultAction;
                $parameters = $this->parseMarkedParameters($parsedUri);
            } else {
                $action = \array_shift($parsedUri);
                if (empty($action)) {
                    $action = $this->defaultAction;
                    $parameters = array();
                } else {
                    if ($this->isMarkedParameter($action)) {
                        \array_unshift($parsedUri, $action);
                        $action = $this->defaultAction;
                        $parameters = $this->parseMarkedParameters($parsedUri);
                    } else {
                        $parameters = $this->parseMarkedParameters($parsedUri);
                    }
                }

            }
        }

        return \compact('controller', 'action', 'parameters');
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
     * @param $uri string
     * @return string
     */
    protected function removeInstallDirectory($uri) {
        $regex = '/^' . $this->installDir . '/';
        $nothing = '';
        return \preg_replace($regex, $nothing, $uri);
    }

    /**
     * Retruns true if the parameter separator is anywhere in the parameter
     * $fragment
     *
     * @param string $fragment
     * @return boolean
     */
    protected function isMarkedParameter($fragment) {
        $found = \preg_match('/:/', $fragment);
        return ($found !== 0 && $found !== false);
    }

    /**
     * Takes an array of parameters and properly parses them for name:value pairs
     *
     * @param array $parameters
     * @return array
     */
    protected function parseMarkedParameters(array $parameters) {
        $parsed = array();
        foreach ($parameters as $parameter) {

            if (!$this->isMarkedParameter($parameter)) {
                $key = null;
                $value = $parameter;
            } else {
                $explodedParameter = \explode(':', $parameter);
                $key = $explodedParameter[0];
                $value = $explodedParameter[1];
            }

            if (empty($key)) {
                $parsed[] = $value;
            } else {
                $parsed[$key] = $value;
            }

        }
        return $parsed;
    }

    /**
     * Generates a normalized controller and action name determined by the routing
     * set forth in the configuration.
     *
     * The array returned will have two keys 'controller' and 'action'.
     *
     * @param array $fragments
     * @return array
     */
    protected function routeRequestedFragments(array $fragments) {
        $controller = \strtolower($fragments['controller']);
        $action = \strtolower($fragments['action']);
        $parameters = $fragments['parameters'];
        $key = $this->getRouteKey($controller, $action);
        $namespace = $this->getRoutedOrDefaultValue($key, 'namespace');
        $controller = $this->getRoutedOrDefaultValue($key, 'controller', $controller);
        $action = $this->getRoutedOrDefaultValue($key, 'action', $action);

        $controller = $namespace . '.' . $this->Normalizer->normalizeController($controller);
        $action = $this->Normalizer->normalizeAction($action);
        $parameters = $this->getRoutedOrDefaultValue($key, 'parameters', $parameters);
        $isStatic = $this->checkRequestIsStatic($key);

        return \compact('controller', 'action', 'parameters', 'isStatic', 'key');
    }

    /**
     * Returns the key of the route in the configuration or false if no key exists
     *
     * @param string $controller
     * @param string $action
     * @return mixed
     */
    protected function getRouteKey($controller, $action) {
        $key = $controller . '/' . $action;
        if (\array_key_exists($key, $this->config['routes'])) {
            return $key;
        } else {
            if (\array_key_exists($controller, $this->config['routes'])) {
                return $controller;
            }
        }
        return false;
    }

    /**
     * @param mixed $routeKey
     * @param string $valueKey
     * @param string $givenValue
     * @return type
     */
    protected function getRoutedOrDefaultValue($routeKey, $valueKey, $givenValue = null) {
        if ($routeKey !== false && isset($this->config['routes'][$routeKey][$valueKey])) {
            return $this->config['routes'][$routeKey][$valueKey];
        } else {
            if (empty($givenValue)) {
                return $this->config['defaults'][$valueKey];
            }
        }
        return $givenValue;
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
        if ($layoutPath[0] === 'libsPath') {
            \array_shift($layoutPath);
            $layoutPath = $this->Paths->getLibsPath($layoutPath);
        } else {
            if ($layoutPath[0] === 'appPath') {
                \array_shift($layoutPath);
            }
            $layoutPath = $this->Paths->getAppPath($layoutPath);
        }

        if ($templatePath[0] === 'libsPath') {
            \array_shift($templatePath);
            $templatePath = $this->Paths->getLibsPath($templatePath);
        } else {
            if ($templatePath[0] === 'appPath') {
                \array_shift($templatePath);
            }
            $templatePath = $this->Paths->getAppPath($templatePath);
        }

        $data = array(
            'layout' => $layoutPath,
            'template' => $templatePath
        );
        $this->StaticFilesStorage->attach($RoutedRequest, $data);
    }

}