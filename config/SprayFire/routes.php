<?php

/**
 * Holds the route configuration that is used by SprayFire.Http.Routing.FireRouting.Router
 * to determin which controller and action should be instantiated and invoked for
 * a request's response.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
use \SprayFire\Http\Routing\FireRouting as FireRouting;

// Create your routes here by creating a new SprayFire.Http.Route object, passing
// the appropriate regex pattern to match the URI path on, the namespace controllers
// stored in and finally the controller and action to invoke for the matched route.
$RootDirectoryRoute = new FireRouting\Route('/', 'SprayFire.Controller.FireController');
$DebugRoute = new FireRouting\Route('/debug/', 'SprayFire.Controller.FireController');
$AboutRoute = new FireRouting\Route('/about/', 'SprayFire.Controller.FireController', 'About', 'sprayfire');

// Make sure you add each of your newly created Route objects to the $RouteBag
$RouteBag = new FireRouting\RouteBag();
$RouteBag->addRoute($RootDirectoryRoute);
$RouteBag->addRoute($DebugRoute);
$RouteBag->addRoute($AboutRoute);

// Service container definition in enivornment.php expects this file to return $RouteBag
return $RouteBag;