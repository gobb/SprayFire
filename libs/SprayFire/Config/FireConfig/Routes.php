<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Config\FireConfig;

use \SprayFire\Config\Routes as ConfigRoutes,
    \SprayFire\Config\FireConfig\Base as FireConfigBase;

class Routes extends FireConfigBase implements ConfigRoutes {

    /**
     * @param string $routeKey
     */
    public function getRoute($routeKey) {
        return $this->get($routeKey);
    }

    /**
     * @param string $routeKey
     */
    public function hasRoute($routeKey) {
        return $this->has($routeKey);
    }

    /**
     * @param string $routeKey
     * @param array $routeOptions
     */
    public function addRoute($routeKey, array $routeOptions) {
        $this->set($routeKey, $routeOptions);
    }


}