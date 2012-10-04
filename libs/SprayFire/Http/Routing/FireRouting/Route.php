<?php

/**
 * Implementation of SprayFire.Http.Routing.Route provided by the default SprayFire
 * install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http as SFHttp,
    \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class Route extends SFCoreObject implements SFRouting\Route {

    /**
     * Stores the pattern used to match against an HTTP URI query path
     *
     * @property string
     */
    protected $regexPattern;

    /**
     * Java or PHP namespaced controller to invoke for the given route.
     *
     * @property string
     */
    protected $namespacedController;

    /**
     * The name of the action to invoke on the namespaced controller.
     *
     * @property string
     */
    protected $action;

    /**
     *
     * @property mixed
     */
    protected $httpMethod;

    /**
     *
     *
     * @param string $regexPattern
     * @param string $namespacedController
     * @param string $action
     * @param mixed $httpMethod
     */
    public function __construct($regexPattern, $namespacedController, $action = 'index', $httpMethod = null) {
        $this->regexPattern = $regexPattern;
        $this->namespacedController = $namespacedController;
        $this->action = $action;
        $this->httpMethod = $httpMethod;
    }

    /**
     *
     * @return string
     */
    public function getPattern() {
        return $this->regexPattern;
    }

    /**
     *
     * @return string
     */
    public function getMethod() {
        return $this->httpMethod;
    }

    /**
     *
     * @return string
     */
    public function getNamespacedController() {
        return $this->namespacedController;
    }

    /**
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

}