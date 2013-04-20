<?php

/**
 * Interface to allow the algorithm to use for matching a route to a request
 * be flexible and dynamic.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Routing;

use \SprayFire\Http,
    \SprayFire\Object;

/**
 * @package SprayFire
 * @subpackage Routing.Interface
 */
interface MatchStrategy extends Object {

    /**
     * A value that should be used as the array key for the Route object in return
     * values from the getRouteAndParameters method.
     */
    const ROUTE_KEY = 'Route';

    /**
     * A value that should be used as the array key for the parameters in return
     * values from the getRouteAndParameters method.
     */
    const PARAMETER_KEY = 'parameters';

    /**
     * Matches a $Request to a \SprayFire\Routing\Route stored in the $Bag
     * or otherwise creates a Route implementation to be used during routing.
     *
     * Please note that the array returned should have 2 keys:
     * - MatchStrategy::ROUTE_KEY => The Route object matched
     * - MatchStrategy::PARAMETER_KEY => an array of parameters to pass to the action
     *
     * It is strongly recommended that you use the constants provided by this
     * interface when setting the keys in the array returned from this method.
     *
     * @param \SprayFire\Routing\RouteBag $Bag
     * @param \SprayFire\Http\Request $Request
     * @return array
     */
    public function getRouteAndParameters(RouteBag $Bag, Http\Request $Request);

}
