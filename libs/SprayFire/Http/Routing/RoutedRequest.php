<?php

/**
 * Interface to tell the dispatcher exactly what controller, action and parameters
 * are to be used for the given request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing;

interface RoutedRequest {

    /**
     * Should return the top level namespace for controller that the request was
     * routed to.
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

}