<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Routing\FireRouting;

use \SprayFire\Routing,
    \SprayFire\StdLib,
    \ArrayIterator;

/**
 *
 * @package SprayFire
 * @subpackage Routing.Implementation
 */
class RouteBag extends StdLib\CoreObject implements Routing\RouteBag {

    /**
     * Stores a collection of routes [$routePattern => SprayFire.Http.Routing.Route]
     *
     * @property array
     */
    protected $routes = [];

    /**
     * Stores a route that is returned if a route is attempted to be retrieved
     * and there is no match.
     *
     * @property \SprayFire\Routing\Route
     */
    protected $NoMatchRoute;

    public function __construct(Routing\Route $NoMatchRoute = null) {
        if (\is_null($NoMatchRoute)) {
            $NoMatchRoute = new Route('', 'SprayFireDemo.Controller');
        }
        $this->NoMatchRoute = $NoMatchRoute;
    }

    /**
     * Will store a $Route with the pattern for that route as the given key.
     *
     * @param \SprayFire\Routing\Route $Route
     * @throws \SprayFire\Routing\Exception\DuplicateRouteAdded
     */
    public function addRoute(Routing\Route $Route) {
        $routePattern = $Route->getPattern();
        if ($this->hasRouteWithPattern($routePattern)) {
            $message = 'The given pattern, ' . $routePattern . ', has already been added to ' . __CLASS__ . ' and may not be overwritten.';
            $message .= '  Please see ' . __CLASS__ . '::removeRouteWithPattern.';
            throw new Routing\Exception\DuplicateRouteAdded($message);
        }
        $this->routes[$routePattern] = $Route;
    }

    /**
     * @param string $pattern
     * @return \SprayFire\Routing\Route
     */
    public function getRoute($pattern = null) {
        if (\is_null($pattern) || !$this->hasRouteWithPattern($pattern)) {
            return $this->NoMatchRoute;
        }
        return $this->routes[$pattern];
    }

    /**
     * Will remove a \SprayFire\Http\Routing\Route from the collection that matches
     * $pattern if it has been added.
     *
     * @param string $pattern
     */
    public function removeRouteWithPattern($pattern) {
        if ($this->hasRouteWithPattern($pattern)) {
            unset($this->routes[$pattern]);
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
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->routes);
    }

}
