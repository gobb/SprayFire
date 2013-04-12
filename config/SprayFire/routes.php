<?php

/**
 * Holds the route configuration that is used by \SprayFire\Http\Routing\FireRouting\Router
 * to determine which controller and action should be instantiated and invoked for
 * a request's response.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
use \SprayFire\Http\Routing\FireRouting as FireRouting;

// Create your routes here by creating a new \SprayFire\Http\Route object, passing
// the appropriate regex pattern to match the URI path on, the namespace controllers
// stored in and finally the controller and action to invoke for the matched route.
$NotFoundRoute = new FireRouting\Route('404', 'SprayFireDemo.Controller', 'NotFound', 'index');
$RootDirectoryRoute = new FireRouting\Route('/', 'SprayFireDemo.Controller', 'Pages', 'index');
$DebugRoute = new FireRouting\Route('/debug/', 'SprayFireDemo.Controller', 'Pages', 'debug');
$AboutRoute = new FireRouting\Route('/about/', 'SprayFireDemo.Controller', 'About', 'sprayfire');

/** IMPORTANT! Ensure that you add your created Route to the RouteBag! IMPORTANT! */

// Make sure you add each of your newly created Route objects to the $RouteBag
// You can inject a Route object into the constructor of this object to be used
// as the route that is returned if a non-matching pattern is passed to
$RouteBag = new FireRouting\RouteBag($NotFoundRoute);
$RouteBag->addRoute($RootDirectoryRoute);
$RouteBag->addRoute($DebugRoute);
$RouteBag->addRoute($AboutRoute);

/*
 * This file must ALWAYS return a \SprayFire\Http\Routing\RouteBag implementation,
 * if an appropriate type is not returned an empty $RouteBag will be created and
 * used for the routing process. An empty $RouteBag may result in your routing
 * MatchStrategy not working as intended if it relies on the $RouteBag being filled.
 */
return $RouteBag;
