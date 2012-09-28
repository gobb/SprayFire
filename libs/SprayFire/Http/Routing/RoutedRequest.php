<?php

/**
 * Interface that represents data about the requested resource after the
 * SprayFire.Http.Routing.Router has routed a SprayFire.Http.Request
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing;

/**
 * This is, in effect, a Data Transfer Object intended to provide pertinent information
 * about the routed resource to SprayFire.Dispatcher.Dispatcher implementations.
 *
 * @package SprayFire
 * @subpackage Http.Routing
 */
interface RoutedRequest {

    /**
     * Should return the top level namespace for the routed request.
     *
     * @return string
     */
    public function getAppNamespace();

    /**
     * The full namespaced name of the class to use; can be Java or PHP style
     * syntax.
     *
     * @return string
     */
    public function getController();

    /**
     * The name of the method or action to invoke on the controller.
     *
     * @return string
     */
    public function getAction();

    /**
     * An array of string values that should be passed to the invoked action.
     *
     * @return array
     */
    public function getParameters();

    /**
     * Returns if the request is not dynamic and should skip the normal Controller
     * processing logic.
     *
     * @return boolean
     */
    public function isStatic();

}