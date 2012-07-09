<?php

/**
 * Simple base class providing a means to retrieve a given controller, action and
 * parameters that should be invoked for a given request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing;

use \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\CoreObject as CoreObject;

class StandardRoutedRequest extends CoreObject implements RoutedRequest {

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
     * @param string $controller
     * @param string $action
     * @param array $parameters
     */
    public function __construct($controller, $action, array $parameters) {
        $this->appNamespace = $this->getTopLevelNamespace($controller);
        $this->controller = (string) $controller;
        $this->action = (string) $action;
        $this->parameters = $parameters;
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
}