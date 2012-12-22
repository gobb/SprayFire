<?php

/**
 * Interface designed to get an application initialized based on the results
 * of routing passed via SprayFire.Http.Routing.RoutedRequest
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Http\Routing as SFRouting;

/**
 * Implementations of this interface should take care of whatever is needed to
 * get an application booted up so that it may take whatever actions are necessary
 * to process a request.
 *
 * At the bare minimum this should include setting up autoloading for the application
 * and, if applicable, instantiate and run any SprayFire.Bootstrap.Bootstrappers
 * that may be assigned to the application.
 *
 * @package SprayFire
 * @subpackage Dispatcher
 */
interface AppInitializer {

    /**
     * Based on information from the $RoutedRequest initialize any bootstrapping
     * process needed.
     *
     * @param \SprayFire\Http\Routing\RoutedRequest $RoutedRequest
     * @return void
     */
    public function initializeApp(SFRouting\RoutedRequest $RoutedRequest);

}
