<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\CoreObject as SFCoreObject;

class RouteBag extends SFCoreObject implements \Countable, \IteratorAggregate {

    /**
     *
     *
     * @property array
     */
    protected $routes = array();

    /**
     *
     * @param SprayFire.Http.Routing.Route $Route
     */
    public function addRoute(SFRouting\Route $Route) {
        $routePattern = $Route->getPattern();
        $this->routes[$routePattern] = $Route;
    }

    public function removeRouteWithPattern($pattern) {
        foreach($this->routes as $routePattern => $Route) {
            if ($routePattern === $pattern) {
                $this->routes[$routePattern] = null;
                unset($this->routes[$routePattern]);
                break;
            }
        }
    }

    /**
     * @param string $pattern
     * @return boolean
     */
    public function hasRouteWithPattern($pattern) {
        return \array_key_exists($pattern, $this->routes);
    }

    /**
     *
     * @return int
     */
    public function count() {
        return \count($this->routes);
    }

    public function getIterator() {
        return new \ArrayIterator($this->routes);
    }

}