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
     * @param SprayFire.Http.Routing.Normalizer $Normalizer
     * @param string $configPath
     * @param string $installDir
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    public function __construct(Normalizer $Normalizer, $configPath, $installDir = '') {
        $this->Normalizer = $Normalizer;
        $this->config = $this->generateConfig($configPath);
        $this->installDir = (string) $installDir;
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
        $controller = $this->generateFullControllerName($fragments['controller']);
        $action = $this->generateActionName($fragments['action']);
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
            $controller = '';
            $action = '';
            $parameters = array();
        } else {
            if ($this->isMarkedParameter($controller)) {
                \array_unshift($parsedUri, $controller);
                $controller = '';
                $action = '';
                $parameters = $this->parseMarkedParameters($parsedUri);
            } else {
                $action = \array_shift($parsedUri);
                if (empty($action)) {
                    $action = '';
                    $parameters = array();
                } else {
                    if ($this->isMarkedParameter($action)) {
                        \array_unshift($parsedUri, $action);
                        $action = '';
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
     * Appends the namespace to the normalized controlleras defined by the routes
     * configuration.
     *
     * @param string $controller
     * @return string
     */
    protected function generateFullControllerName($controller) {
        $namespace = $this->config['defaults']['namespace'];
        return $namespace . '.' . $this->Normalizer->normalizeController($controller);
    }

    /**
     * Generates the normalized action as defined by the routes configuration.
     *
     * @param string $action
     * @return string
     */
    protected function generateActionName($action) {
        return $this->Normalizer->normalizeAction($action);
    }

}