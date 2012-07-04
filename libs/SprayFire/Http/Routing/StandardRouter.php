<?php

/**
 * Base class to route an HTTP request to the appropriate controller, action and
 * parameters.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing;

use \SprayFire\Http\Routing\Router as Router,
    \SprayFire\Http\Request as Request,
    \SprayFire\CoreObject as CoreObject;

class StandardRouter extends CoreObject implements Router {

    /**
     * Will create a RoutedRequest appropriate to the passed Request object
     *
     * @param SprayFire.Http.Request $Request
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function getRoutedRequest(Request $Request) {

    }
}