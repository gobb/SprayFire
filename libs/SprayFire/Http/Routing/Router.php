<?php

/**
 * Interface implemented by services that determine what controller, action and
 * parameters should be used for a given request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing;

use \SprayFire\Http\Request as Request;

interface Router {

    /**
     * @param SprayFire.Http.Requst $Request
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function getRoutedRequest(Request $Request);

}