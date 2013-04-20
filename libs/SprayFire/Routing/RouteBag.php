<?php

/**
 * Interface for a data structure object that allows storing and retrieving of
 * \SprayFire\Routing\Route objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Routing;

use \SprayFire\Object,
    \Countable,
    \IteratorAggregate;

/**
 * @package SprayFire
 * @subpackage Http.Routing
 */
interface RouteBag extends Object, Countable, IteratorAggregate {

    /**
     * Add a $Route implementation to the bag, if the $Route has already been added
     * throw an exception as this would result in various functionality not working
     * properly.
     *
     * @param \SprayFire\Routing\Route $Route
     * @return void
     * @throws \SprayFire\Routing\Exception\DuplicateRouteAdded
     */
    public function addRoute(Route $Route);

    /**
     * Get the $Route added to the bag matching the given pattern, if the pattern
     * is not matched or null is passed then the bag should return a default
     * \SprayFire\Http\Routing\Route implementation.
     *
     * Please note that this method must always return \SprayFire\Routing\Route
     * or terrible, awful things are bound to happen.
     *
     * @param string|null $pattern
     * @return \SprayFire\Routing\Route
     */
    public function getRoute($pattern = null);

    /**
     * Remove any $Route associated to a matched pattern.
     *
     * @param string $pattern
     * @return void
     */
    public function removeRouteWithPattern($pattern);

    /**
     * Determines if a $Route was added that would match a given pattern.
     *
     * @param string $pattern
     * @return boolean
     */
    public function hasRouteWithPattern($pattern);

}
