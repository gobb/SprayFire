<?php

/**
 * Interface to allow the algorithm to use for matching a route to a request
 * be flexible and dynamic.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Http\Routing;

use \SprayFire\Http as SFHttp,
    \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Http.Routing
 */
interface MatchStrategy extends SFObject {

    /**
     * Matches a $Request to a \SprayFire\Http\Routing\Route stored in the $Bag
     * or otherwise creates a Route implementation to be used during routing.
     *
     * Please note that the array returned should have 2 keys:
     * - Route => The Route object matched
     * - parameters => an array of parameters to pass to the action
     *
     * @param \SprayFire\Http\Routing\RouteBag $Bag
     * @param \SprayFire\Http\Request $Request
     * @return array
     */
    public function getRouteAndParameters(RouteBag $Bag, SFHttp\Request $Request);

}
