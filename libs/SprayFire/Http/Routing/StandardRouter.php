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
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\Http\Routing\Normalizer as Normalizer,
    \SprayFire\Exception\FatalRuntimeException as FatalRunTimeException;

class StandardRouter extends CoreObject implements Router {

    /**
     * @property SprayFire.Http.Routing.Normalizer
     */
    protected $Normalizer;

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
     * @param SprayFire.Http.Routing.Normalizer $Normalizer
     * @param string $configPath
     * @param string $installDir
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    public function __construct(Normalizer $Normalizer, $configPath, $installDir = '') {
        $this->Normalizer = $Normalizer;
        $this->config = $this->generateConfig($configPath);
        $this->installDir = (string) $installDir;
        $this->defaultController = (string) $this->config['defaults']['controller'];
        $this->defaultAction = (string) $this->config['defaults']['action'];
        $this->defaultParameters = (array) $this->config['defaults']['parameters'];

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

    /**
     * Will create a RoutedRequest appropriate to the passed Request object
     *
     * @param SprayFire.Http.Request $Request
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function getRoutedRequest(Request $Request) {
        $Uri = $Request->getUri();
        $path = $Uri->getPath();
        $cleanPath = $this->cleanPath($path);
        $fragments = $this->parseFragments($cleanPath);
        $controllerAndAction = $this->generateControllerAndActionName($fragments['controller'], $fragments['action']);
        $controller = $controllerAndAction['controller'];
        $action = $controllerAndAction['action'];
        $parameters = $fragments['parameters'];
        return new \SprayFire\Http\Routing\StandardRoutedRequest($controller, $action, $parameters);
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
     * @param string $controller
     * @param string $action
     * @return array
     */
    protected function generateControllerAndActionName($controller, $action) {
        $controller = \strtolower($controller);
        $action = \strtolower($action);
        $key = $this->getRouteKey($controller, $action);
        if ($key !== false) {
            if (isset($this->config['routes'][$key]['namespace'])) {
                $namespace = $this->config['routes'][$key]['namespace'];
            } else {
                $namespace = $this->config['defaults']['namespace'];
            }

            if (isset($this->config['routes'][$key]['controller'])) {
                $controller = $this->config['routes'][$key]['controller'];
            }

            if (isset($this->config['routes'][$key]['action'])) {
                $action = $this->config['routes'][$key]['action'];
            }

        } else {
            $namespace = $this->config['defaults']['namespace'];
        }
        $controller = $namespace . '.' . $this->Normalizer->normalizeController($controller);
        $action = $this->Normalizer->normalizeAction($action);

        return \compact('controller', 'action');
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

}