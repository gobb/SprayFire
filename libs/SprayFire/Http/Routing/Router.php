<?php

/**
 * Interface to determine the appropriate resource to provide to the user based
 * off of a given SprayFire.Http.Request
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing;

use \SprayFire\Http as SFHttp;

/**
 * Implementations should accept some configuration that dictates what information
 * should be passed in a \SprayFire\Http\Routing\RoutedRequest when routing is
 * processed.
 *
 * @package SprayFire
 * @subpackage Http.Routing
 *
 * @todo
 * We should take a look at how we are dealing with static file paths.  Perhaps
 * we should abstract routes out into a series of interfaces.  We need some more
 * thought on how to deal with the problem of static versus dynamic requests.
 */
interface Router {

    /**
     * By matching various pieces of information from the $Request should return
     * a \SprayFire\Http\Routing\RoutedRequest based on some configuration passed
     * to the implementation.
     *
     * @param \SprayFire\Http\Request $Request
     * @return \SprayFire\Http\Routing\RoutedRequest
     */
    public function getRoutedRequest(SFHttp\Request $Request);

}
