<?php

/**
 * An implementation of \SprayFire\Http\Routing\FireRouting\MatchStrategy that
 * will only match a route based on a configuration.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http as SFHttp,
    \SprayFire\Http\Routing as SFHttpRouting;

/**
 * If a $Route is not matched to the request's URI path the default $Route for the
 * bag passed will be used.
 *
 * A $Route is considered matched to a request's URI path with a regular expression
 * check. The pattern from the $Route is checked against the string strictly ensuring
 * that the beginning and end of the path matches the pattern exactly.
 *
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class ConfigurationMatchStrategy extends MatchStrategy {

    /**
     * @param \SprayFire\Http\Routing\RouteBag $Bag
     * @param \SprayFire\Http\Request $Request
     * @return array
     */
    public function getRouteAndParameters(SFHttpRouting\RouteBag $Bag, SFHttp\Request $Request) {
        if (\count($Bag) === 0) {
            return [
                self::ROUTE_KEY => $Bag->getRoute(),
                self::PARAMETER_KEY => []
            ];
        }

        $path = '/' . $this->removeInstallDirectory($Request->getUri()->getPath()) . '/';
        $method = \strtoupper($Request->getMethod());

        foreach ($Bag as $Route) {
            /* @var \SprayFire\Http\Routing\Route $Route */
            $routePattern = '#^' .  $Route->getPattern() . '$#';
            $routeMethod = \strtoupper($Route->getMethod());

            // Check for trueness in case null is returned from Route::getMethod
            // if a falsey value is returned don't check the HTTP method
            if ($routeMethod && $routeMethod !== $method) {
                continue;
            }

            if (\preg_match($routePattern, $path, $match)) {
                foreach ($match as $key => $val) {
                    // Ensures that numeric keys are removed from the match array
                    // only returning the appropriate named groups.
                    if ($key === (int) $key) {
                        unset($match[$key]);
                    }
                }
                return [
                    self::ROUTE_KEY => $Route,
                    self::PARAMETER_KEY => $match
                ];
            }
        }

        return [
            self::ROUTE_KEY => $Bag->getRoute(),
            self::PARAMETER_KEY => []
        ];
    }

}
