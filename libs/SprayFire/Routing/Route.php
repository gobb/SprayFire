<?php

/**
 * Interface representing a HTTP URI route that can be matched against an HTTP
 * request to determine what resources are used to generate the response.
 *
 * @author  Charles Sprayberry
 * @license https://github.com/cspray/SprayFire/blob/master/config/SprayFire/routes.php
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Routing;

use \SprayFire\Object;


/**
 * @package SprayFire
 * @subpackage Routing.Interface
 */
interface Route extends Object {

    /**
     * Return the pattern, presumably regex, that will be used to match against
     * the given HTTP request URI.
     *
     * @return string
     */
    public function getPattern();

    /**
     * Return an HTTP method that the Route should restrict requests against.
     *
     * @return mixed
     */
    public function getMethod();

    /**
     * The namespace that the controller belongs to.
     *
     * For example, if you were going to return the namespace for all SprayFire
     * provided controllers you would return 'SprayFire.Controller.FireController'
     *
     * @return string
     */
    public function getControllerNamespace();

    /**
     * Returns the name of the controller that we should instantiate, it should
     * exist in the namespace returned by Route::getControllerNamespace();
     *
     *
     * @return string
     */
    public function getControllerClass();

    /**
     * The name of the action to invoke on the controller.
     *
     * @return string
     */
    public function getAction();

}
