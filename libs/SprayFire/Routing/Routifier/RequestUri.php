<?php

/**
 * @file
 * @brief Stores a class that is used to parse a requested URI into segments that
 * can be used for completing the request.
 */

namespace SprayFire\Routing\Routifier;

/**
 * @brief
 */
class RequestUri extends \SprayFire\Util\CoreObject implements \SprayFire\Routing\Uri {

    /**
     * @brief The string that represents the directory the SprayFire framework
     * is installed in
     *
     * @var $installDir
     */
    protected $installDir;

    /**
     * @brief The string that represents the complete URI that was requested
     *
     * @var $uri
     */
    protected $uri;

    /**
     * @brief An array of URI fragments
     *
     * @var $parsedUri
     */
    protected $parsedUri;

    /**
     * @brief A string representing the controller requested
     *
     * @var $controllerFragment
     */
    protected $controllerFragment;

    /**
     * @brief A string representing the action requested
     *
     * @var $actionFragment
     */
    protected $actionFragment;

    /**
     * @brief An array of strings representing the parameters for the action
     * requested
     *
     * @var $parameters
     */
    protected $parameters;

    /**
     * The value set to controller fragment if there was no controller requested
     *
     * @var $noController
     */
    protected $noController = \SprayFire\Routing\Uri::NO_CONTROLLER_REQUESTED;

    /**
     * The value set to action fragment if there was no action requested
     *
     * @var $noAction
     */
    protected $noAction = \SprayFire\Routing\Uri::NO_ACTION_REQUESTED;

    /**
     * An empty array set to the parameters if there were no parameters requested
     *
     * @var $noParameters
     */
    protected $noParameters = array();

    /**
     * A string that represents the separator for parameter key/value pairs
     *
     * @var $parameterSeparator
     */
    protected $parameterSeparator = \SprayFire\Routing\Uri::PARAMETER_SEPARATOR;

    /**
     * @param $uri string
     * @param $installDir string
     */
    public function __construct($uri, $installDir) {
        $uri = \urldecode($uri);
        $this->uri = $uri;
        $this->installDir = $installDir;
        $this->parseUri();
    }

    /**
     * @brief Will set the properties needed to return the appropriate values in
     * getter functions after the requested URI has been parsed.
     *
     * @details
     * The URI parsed will be in the format:
     *
     * /controller/action/parameter/parameter/parameter
     *
     * Note that the install directory for the SprayFire framework driven application
     * will be removed from the beginning of the URI as well.
     */
    protected function parseUri() {
        $uri = $this->uri;
        $uri = $this->removeLeadingForwardSlash($uri);
        $uri = $this->removeInstallDirectory($uri);
        $uri = $this->removeLeadingForwardSlash($uri);

        $slash = '/';
        $this->parsedUri = \explode($slash, $uri);
        $this->setFragments();
    }

    /**
     * @brief Will take the fragments in the parsed URI and set the appropriate
     * properties to the appropriate values based on what fragments are present
     * and the type of fragment.
     *
     * @details
     *
     */
    protected function setFragments() {
        $parsedUri = $this->parsedUri;
        $controller = \trim(\array_shift($parsedUri));
        if (empty($controller)) {
            $controller = $this->noController;
            $action = $this->noAction;
            $parameters = $this->noParameters;
        } else {
            if ($this->isMarkedParameter($controller)) {
                \array_unshift($parsedUri, $controller);
                $controller = $this->noController;
                $action = $this->noAction;
                $parameters = $this->parseMarkedParameters($parsedUri);
            } else {
                $action = \array_shift($parsedUri);
                if (empty($action)) {
                    $action = $this->noAction;
                    $parameters = $this->noParameters;
                } else {
                    if ($this->isMarkedParameter($action)) {
                        \array_unshift($parsedUri, $action);
                        $action = $this->noAction;
                        $parameters = $this->parseMarkedParameters($parsedUri);
                    } else {
                        $parameters = $this->parseMarkedParameters($parsedUri);
                    }
                }

            }
        }

        $this->controllerFragment = $controller;
        $this->actionFragment = $action;
        $this->parameters = $parameters;
    }

    protected function removeLeadingForwardSlash($uri) {
        $regex = '/^\//';
        $nothing = '';
        return preg_replace($regex, $nothing, $uri);
    }

    protected function removeInstallDirectory($uri) {
        $regex = '/^' . $this->installDir . '/';
        $nothing = '';
        return preg_replace($regex, $nothing, $uri);
    }

    protected function isMarkedParameter($fragment) {
        $found = \preg_match('/' . $this->parameterSeparator . '/', $fragment);
        return ($found !== 0 && $found !== false);
    }

    protected function parseMarkedParameters(array $parameters) {
        $parsed = array();
        foreach ($parameters as $parameter) {

            if (!$this->isMarkedParameter($parameter)) {
                $key = null;
                $value = $parameter;
            } else {
                $explodedParameter = \explode($this->parameterSeparator, $parameter);
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
     * @return string
     */
    public function getControllerFragment() {
        return $this->controllerFragment;
    }

    /**
     * @return string
     */
    public function getActionFragment() {
        return $this->actionFragment;
    }

    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getRequestedUri() {
        return $this->uri;
    }

}