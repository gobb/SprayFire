<?php

use \SprayFire\Http\Routing\FireRouting as FireRouting;

$RootDirectoryRoute = new FireRouting\Route('/', 'SprayFire.Controller.FireController');
$DebugRoute = new FireRouting\Route('/debug/', 'SprayFire.Controller.FireController');
$AboutRoute = new FireRouting\Route('/about/', 'SprayFire.Controller.FireController', 'About', 'sprayfire');

$RouteBag = new FireRouting\RouteBag();

$RouteBag->addRoute($RootDirectoryRoute);
$RouteBag->addRoute($DebugRoute);
$RouteBag->addRoute($AboutRoute);

return $RouteBag;