<?php

$routesConfig = array();

$routesConfig['defaults'] = array(
    'namespace' => 'SprayFire.Controller.FireController',
    'controller' => 'Pages',
    'action' => 'index',
    'parameters' => array(),
    'method' => 'GET'
);

$routesConfig['staticDefaults'] = array(
    'static' => false,
    'responderName' => 'SprayFire.Responder.HtmlResponder',
    'layoutPath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php'),
    'templatePath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'debug-content.php')
);

$routesConfig['404'] = array(
    'static' => true,
    'layoutPath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default-no-placeholders.php'),
    'templatePath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', '404.php')
);

$routesConfig['500'] = array(
    'static' => true,
    'templatePath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', '500.php')
);

$routesConfig['routes'] = array();

$routesConfig['routes']['/'] = array(
    // Set the appropriate keys here to change the homepage
);

$routesConfig['routes']['/debug/'] = array(
    'action' => 'debug'
);

$routesConfig['routes']['/about/'] = array(
    'namespace' => 'SprayFire.Controller.FireController',
    'controller' => 'About',
    'action' => 'sprayFire'
);

return $routesConfig;