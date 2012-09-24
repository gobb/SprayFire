<?php

/**
 * Interface designed to get an application, as based on information from
 * SprayFire.Http.Routing.RoutedRequest, started up and its bootstrap is initiated.
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Http\Routing as SFRouting;

/**
 * @package SprayFire
 * @subpackage Dispatcher
 */
interface AppInitializer {

    /**
     * Based on information from the $RoutedRequest initialize any bootstrapping
     * process needed.
     *
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return void
     */
    public function initializeApp(SFRouting\RoutedRequest $RoutedRequest);

}