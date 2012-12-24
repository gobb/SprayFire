<?php

/**
 * Implementation of \SprayFire\Http\Routing\Route provided by the default SprayFire
 * install.
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
     * Java or PHP namespace that the controller exists in.
     *
     * @property string
     */
    protected $namespace;

    /**
     * The name of the class to invoke, it should exist in Route::namespace
     *
     * @property string
     */
    protected $class;

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
     * @param string $namespace
     * @param string $class
     * @param string $action
     * @param mixed $httpMethod
     */
    public function __construct($regexPattern, $namespace, $class = 'Pages', $action = 'index', $httpMethod = null) {
        $this->regexPattern = $regexPattern;
        $this->namespace = $namespace;
        $this->class = $class;
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
    public function getControllerNamespace() {
        return $this->namespace;
    }

    /**
     *
     * @return string
     */
    public function getControllerClass() {
        return $this->class;
    }

    /**
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

}
