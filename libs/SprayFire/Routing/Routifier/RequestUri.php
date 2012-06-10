<?php

/**
 * Used to parse a requested URI into segments that can be used for completing the
 * request.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing\Routifier;

/**
 * @uses SprayFire.Routing.Uri
 * @uses SprayFire.Util.CoreObject
 */
class RequestUri extends \SprayFire\Util\CoreObject implements \SprayFire\Routing\Uri {

    /**
     * The directory that SprayFire is installed i
     *
     * @property string
     */
    protected $installDir;

    /**
     * The actual URI that was requested
     *
     * @property string
     */
    protected $uri;

    /**
     * An array of URI fragments
     *
     * @property array
     */
    protected $parsedUri;

    /**
     * The controller fragment requested in the URI
     *
     * @property string
     */
    protected $controllerFragment;

    /**
     * The action requested in the URI
     *
     * @property string
     */
    protected $actionFragment;

    /**
     * Array of strings representing the parameters for the action requested
     *
     * @property array
     */
    protected $parameters;

    /**
     * The value set to controller fragment if there was no controller requested
     *
     * @property string
     */
    protected $noController = \SprayFire\Routing\Uri::NO_CONTROLLER_REQUESTED;

    /**
     * The value set to action fragment if there was no action requested
     *
     * @property string
     */
    protected $noAction = \SprayFire\Routing\Uri::NO_ACTION_REQUESTED;

    /**
     * An empty array set to the parameters if there were no parameters requested
     *
     * @property array
     */
    protected $noParameters = array();

    /**
     * Represents the separator for parameter key/value pairs
     *
     * @property string
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
     * Will set the properties needed to return the appropriate values in
     * getter functions after the requested URI has been parsed.
     *
     * The URI parsed will be in the format:
     *
     * /controller/action/parameter/parameter/parameter
     *
     * Note that the install directory for the SprayFire framework driven application
     * will be removed from the beginning of the URI as well.
     *
     * @return void
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
     * Will take the fragments in the parsed URI and set the appropriate
     * properties to the appropriate values based on what fragments are present
     * and the type of fragment.
     *
     * @return void
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

    /**
     * Retruns true if the parameter separator is anywhere in the parameter
     * \a $fragment
     *
     * @param $fragment string
     * @return boolean
     */
    protected function isMarkedParameter($fragment) {
        $found = \preg_match('/' . $this->parameterSeparator . '/', $fragment);
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