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
     * @param $uri string
     * @param $installDir string
     */
    public function __construct($uri, $installDir) {
        $uri = \urldecode($uri);
        $this->uri = $uri;
        $this->installDir = $installDir;
        $this->parseUri();
    }

    protected function parseUri() {
        $uri = $this->uri;
        $uri = $this->removeLeadingForwardSlash($uri);
        $uri = $this->removeInstallDirectory($uri);
        $uri = $this->removeLeadingForwardSlash($uri);

        $slash = '/';
        $this->parsedUri = \explode($slash, $uri);
        $this->setFragments();
    }

    protected function setFragments() {
        $parsedUri = $this->parsedUri;
        $controller = \array_shift($parsedUri);
        if (empty($controller)) {
            $controller = \SprayFire\Routing\Uri::NO_CONTROLLER_REQUESTED;
            $action = \SprayFire\Routing\Uri::NO_ACTION_REQUESTED;
            $parameters = array();
        } else {
            $action = \array_shift($parsedUri);
            $parameters = $parsedUri;
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
     * @return string
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