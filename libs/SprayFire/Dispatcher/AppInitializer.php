<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Http\Routing\RoutedRequest as RoutedRequest;

interface AppInitializer {

    public function intializeApp(RoutedRequest $RoutedRequest);

}