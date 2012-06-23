<?php

/**
 * Holds a class that will determine the controller, action and parameters should
 * be used for request processing
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing\Routifier;

class Request extends \SprayFire\CoreObject implements \SprayFire\Routing\Request {

    /**
     * The URI requested by the user
     *
     * @property string
     */
    protected $uri;

    /**
     * Name of the installDir holding the web accessible folder.
     *
     * Used to ensure that the install directory does not inadvertently become
     * the controller for requests such as www.example.com/install/real_controller/real_action
     *
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
     * @property string
     */
    protected $defaultParameters;

    /**
     * @property string
     */
    protected $controller;

    protected $action;

    protected $parameters;

    public function __construct($uri, $installDir, $defaultController = 'page', $defaultAction = 'index') {
        $this->uri = (string) $uri;
        $this->installDir = (string) $installDir;
        $this->defaultController = (string) $defaultController;
        $this->defaultAction = (string) $defaultAction;
        $this->defaultParameters = array();
        $this->setUriFragments();
    }

    public function getUri() {
        return $this->uri;
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getParameters() {
        return $this->parameters;
    }

    protected function setUriFragments() {
        $uri = $this->cleanUpUri($this->uri);
        $this->parseUri($uri);
    }

    protected function parseUri($uri) {
        $parsedUri = \explode('/', $uri);
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
                    $parameters = $this->defaultParameters;
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

        $this->controller = $controller;
        $this->action = $action;
        $this->parameters = $parameters;

    }

    /**
     * Retruns true if the parameter separator is anywhere in the parameter
     * \a $fragment
     *
     * @param $fragment string
     * @return boolean
     */
    protected function isMarkedParameter($fragment) {
        $found = \preg_match('/:/', $fragment);
        return ($found !== 0 && $found !== false);
    }

    /**
     * Takes an array of parameters and properly parses them for name:value pairs
     *
     * @param $parameters array
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
     * Removes all leading forward slashes and the installDir from the URI passed
     *
     * @return string
     */
    protected function cleanUpUri($uri) {
        $uri = $this->removeLeadingForwardSlash($uri);
        $uri = $this->removeInstallDirectory($uri);
        $uri = $this->removeLeadingForwardSlash($uri);
        return $uri;
    }

    /**
     * @param $uri string
     * @return string
     */
    protected function removeLeadingForwardSlash($uri) {
        $regex = '/^\//';
        $nothing = '';
        return preg_replace($regex, $nothing, $uri);
    }

    /**
     * @param $uri string
     * @return string
     */
    protected function removeInstallDirectory($uri) {
        $regex = '/^' . $this->installDir . '/';
        $nothing = '';
        return preg_replace($regex, $nothing, $uri);
    }
}