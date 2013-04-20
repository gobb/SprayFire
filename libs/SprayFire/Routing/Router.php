<?php

/**
 * Interface to determine the appropriate resource to provide to the user based
 * off of a given \SprayFire\Http\Request
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Routing;

use \SprayFire\Http;

/**
 * Implementations should accept some configuration that dictates what information
 * should be passed in a \SprayFire\Routing\RoutedRequest when routing is
 * processed.
 *
 * @package SprayFire
 * @subpackage Routing.Interface
 */
interface Router {

    /**
     * By matching various pieces of information from the $Request should return
     * a \SprayFire\Http\Routing\RoutedRequest based on some configuration passed
     * to the implementation.
     *
     * @param \SprayFire\Http\Request $Request
     * @return \SprayFire\Routing\RoutedRequest
     */
    public function getRoutedRequest(Http\Request $Request);

}
