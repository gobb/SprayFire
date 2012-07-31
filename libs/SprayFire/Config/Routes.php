<?php

/**
 *
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Config;

interface Routes {

    /**
     * @param string $routeKey
     */
    public function getRoute($routeKey);

    /**
     * @param string $routeKey
     */
    public function hasRoute($routeKey);

    /**
     * @param string $routeKey
     * @param array $routeOptions
     */
    public function addRoute($routeKey, array $routeOptions);

}