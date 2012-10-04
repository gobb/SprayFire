<?php

/**
 * Interface representing a HTTP URI route that can be matched against an HTTP
 * request to determine what resources are used to generate the response.
 *
 * @author  Charles Sprayberry
 * @license https://github.com/cspray/SprayFire/blob/master/config/SprayFire/routes.php
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing;

use \SprayFire\Object as SFObject,
    \SprayFire\Http as SFHttp;


/**
 * @package SprayFire
 * @subpackage Http.Routing
 */
interface Route extends SFObject {

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
     * The fully namespaced controller can be in Java or PHP style.
     *
     * @return string
     */
    public function getNamespacedController();

    /**
     * The name of the action to invoke on the controller.
     *
     * @return string
     */
    public function getAction();

}