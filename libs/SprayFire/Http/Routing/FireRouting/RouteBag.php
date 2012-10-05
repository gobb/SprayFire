<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\CoreObject as SFCoreObject;

/**
 *
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class RouteBag extends SFCoreObject implements \Countable, \IteratorAggregate {

    /**
     * Stores a collection of routes [$routePattern => SprayFire.Http.Routing.Route]
     *
     * @property array
     */
    protected $routes = array();

    /**
     * Will store a $Route with the pattern for that route as the given key.
     *
     * @param SprayFire.Http.Routing.Route $Route
     */
    public function addRoute(SFRouting\Route $Route) {
        $routePattern = $Route->getPattern();
        if ($this->hasRouteWithPattern($routePattern)) {
            $message = 'The given pattern, ' . $routePattern . ', has already been added to ' . __CLASS__ . ' and may not be overwritten.';
            $message .= '  Please see ' . __CLASS__ . '::removeRouteWithPattern.';
            throw new \InvalidArgumentException($message);
        }
        $this->routes[$routePattern] = $Route;
    }

    /**
     * Will remove a SprayFire.Http.Routing.Route from the collection that matches
     * $pattern if it has been added.
     *
     * @param string $pattern
     */
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
     * @return int
     */
    public function count() {
        return \count($this->routes);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->routes);
    }

}