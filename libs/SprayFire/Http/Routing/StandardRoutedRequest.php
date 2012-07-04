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
    protected $controller = '';

    /**
     * @property string
     */
    protected $action = '';

    /**
     * @property array
     */
    protected $parameters = array();

    public function __construct($controller, $action, array $parameters) {
        $this->controller = (string) $controller;
        $this->action = (string) $action;
        $this->parameters = $parameters;
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