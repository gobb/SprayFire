<?php

/**
 * Implementation of \SprayFire\Http\Routing\MatchStrategy that will determine a
 * Route based on a convention of how to parse a pretty URL.
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
 * This implementation will take a pretty URL and split it into the appropriate
 * controller, action and parameters to use for a returned \SprayFire\Http\Routing\Route.
 *
 * The format of pretty URLs mapping to the appropriate information is below. It
 * is fairly common in the web framework world:
 *
 * /controller/action/param1/param2
 *
 * Optionally parameters can be named by separating the parameter key and the value
 * by a colon:
 *
 * /controller/action/named:param/other:param
 *
 * Some information needed to complete Route contract is not provided by the path,
 * namely the namespace for the controller. Potentially the controller and action
 * itself could be unknown based on the path so a series of options are used. You
 * can configure the options used for the namespace and defaulted values when none
 * could be determined from the path by passing an associative array to the class
 * constructor. Please review the docs for that method to see what options are
 * made available to you.
 *
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class ConventionMatchStrategy extends MatchStrategy {

    /**
     * The actual array of options used by the algorithm to populate the appropriate
     * information for the Route object.
     *
     * @property array
     */
    protected $options = array();

    /**
     * A set of options that are used as default values if they aren't provided
     * in the constructor parameter.
     *
     * @property array
     */
    private $defaultOptions = array(
        'namespace' => 'SprayFire.Controller.FireController',
        'controller' => 'Pages',
        'action' => 'index',
        'installDirectory' => ''
    );

    /**
     * The set of options passed will replace any default options if the appropriate
     * keys are set.
     *
     * Options available:
     * -------------------------------------------------------------------------
     * - namespace = The Java or PHP style namespace for the controller
     * - controller = The name of the controller to use, if non-special characters
     *                are used the name will be normalized to be an expected PascalCased
     *                class name.
     * - action = The name of the controller method to invoke
     * - installDirectory = The name of the install directory that should be removed
     *                      from the request path
     *
     * @param array $options
     */
    public function __construct(array $options = array()) {
        $this->options = \array_merge($this->options, $this->defaultOptions);
    }

    /**
     * Matches a $Request to a \SprayFire\Http\Routing\Route stored in the $Bag
     * or otherwise creates a Route implementation to be used during routing.
     *
     * Please note that the array returned should have 2 keys:
     * - MatchStrategy::ROUTE_KEY => The Route object matched
     * - MatchStrategy::PARAMETER_KEY => an array of parameters to pass to the action
     *
     * It is strongly recommended that you use the constants provided by this
     * interface when setting the keys in the array returned from this method.
     *
     * @param \SprayFire\Http\Routing\RouteBag $Bag
     * @param \SprayFire\Http\Request $Request
     * @return array
     */
    public function getRouteAndParameters(SFHttpRouting\RouteBag $Bag, SFHttp\Request $Request) {
        if ($Request->getUri()->getPath() === '/') {
            $Route = new Route('/', $this->options['namespace'], $this->options['controller'], $this->options['action']);
            return array(
                'Route' => $Route,
                'parameters' => array()
            );
        }


    }
}
