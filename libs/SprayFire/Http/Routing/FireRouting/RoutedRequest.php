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

use \SprayFire\Http\Routing as SFHttpRouting,
    \SprayFire\StdLib as SFStdLib;

/**
 * This implementation is a Data Transfer Object to provide data from the
 * SprayFire.Http.Routing to the SprayFire.Dispatcher module.
 *
 * Although this object is not package private it is highly recommended that you
 * do not manually create a RoutedRequest object, instead letting the
 * SprayFire.Http.Routing.FireRouting.Router implement the appropriate RoutedRequest.
 *
 * Ultimately the only functionality provided by this object is parsing the
 * top level namespace from a controller.  This functionality is only provided
 * internally and can be "used" when passing a constructor value.
 *
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class RoutedRequest extends SFStdLib\CoreObject implements SFHttpRouting\RoutedRequest {

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
    protected $parameters = [];

    /**
     * Will parse the app namespace for this RoutedRequest based on the top level
     * of the controller namespace passed.
     *
     * @param string $controller
     * @param string $action
     * @param array $parameters
     */
    public function __construct($controller, $action, array $parameters = []) {
        $this->appNamespace = $this->getTopLevelNamespace($controller);
        $this->controller = (string) $controller;
        $this->action = (string) $action;
        $this->parameters = $parameters;
    }

    /**
     * Will return the top level namespace for a class, when the namespace is
     * separated by dots.
     *
     * @param string $controller
     * @return string
     */
    protected function getTopLevelNamespace($controller) {
        $namespaces = \explode('.', $controller);
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

}
