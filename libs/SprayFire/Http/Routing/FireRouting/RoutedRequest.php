<?php

/**
 * Implementation of SprayFire.Http.Routing.RoutedRequest provided with the default
 * SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * This implementation is a Data Transfer Object to provide data from the
 * SprayFire.Http.Routing to the SprayFire.Dispatcher module.
 *
 * Ultimately the only functionality provided by this object is parsing the
 * top level namespace from a controller.  This functionality is only provided
 * internally and
 *
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class RoutedRequest extends SFCoreObject implements SFRouting\RoutedRequest {

    /**
     * @property string
     */
    protected $appNamespace = '';

    /**
     * @property string
     */
    protected $controller = '';

    /**
     * @property string
     */
    protected $action = '';

    /**
     * @property array
     */
    protected $parameters = array();

    /**
     * @property boolean
     */
    protected $isStatic;

    /**
     * Will parse the app namespace for this RoutedRequest based on the top level
     * of the controller namespace passed.
     *
     * @param string $controller
     * @param string $action
     * @param array $parameters
     */
    public function __construct($controller, $action, array $parameters, $isStatic = false) {
        $this->appNamespace = $this->getTopLevelNamespace($controller);
        $this->controller = (string) $controller;
        $this->action = (string) $action;
        $this->parameters = $parameters;
        $this->isStatic = $isStatic;
    }

    /**
     * Will return the top level namespace for a controller, whether that namespace
     * is separated by dots or back slashes
     *
     * @param string $controller
     * @return string
     */
    protected function getTopLevelNamespace($controller) {
        if (\strpos($controller, '.') !== false) {
            $delimiter = '.';
        } else {
            $delimiter = '\\';
        }

        $namespaces = \explode($delimiter, $controller);
        if (isset($namespaces[0]) && !empty($namespaces[0])) {
            return $namespaces[0];
        }
        return '';
    }

    /**
     * @return string
     */
    public function getAppNamespace() {
        return $this->appNamespace;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * @return boolean
     */
    public function isStatic() {
        return $this->isStatic;
    }
}