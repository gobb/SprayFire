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
 * 
 *
 * @package SprayFire
 * @subpackage Http.Routing
 */
interface Router {

    /**
     * @param SprayFire.Http.Requst $Request
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function getRoutedRequest(SFHttp\Request $Request);

    /**
     * @return SprayFire.Http.Routing.RoutedRequest
     */
    public function get404RoutedRequest();

    /**
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return array
     */
    public function getStaticFilePaths(RoutedRequest $RoutedRequest);

    /**
     * @param array $configuration
     * @return array
     */
    public function set404Configuration(array $configuration);

}