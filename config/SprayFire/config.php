<?php

if (isset($EnvironmentConfig)) {
    $EnvironmentConfig->setProperty('sprayfire.development_mode', true);
    $EnvironmentConfig->setProperty('sprayfire.request_start_time', $requestStartTime);
}

if (isset($RoutesConfig)) {

    // This route represents the values used if the necessary value is not included
    // in the request or the configuration value is not present in the route
    $RoutesConfig->addRoute('defaults', array(
        'namespace' => 'SprayFire.Controller.FireController',
        'controller' => 'Pages',
        'action' => 'index',
        'parameters' => array(),
        'static' => false,
        'staticDefaults' => array(
            'responderName' => 'SprayFire.Responder.HtmlResponder',
            'layoutPath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php'),
            'templatePath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'debug-content.php')
        )
    ));

    $RoutesConfig->addRoute('/blog/articles/[\d]', array(
        'namespace' => 'SprayFire.Controller.FireController',
        'controller' => 'Blog',
        'action' => 'viewArticle'
    ));

    $RoutesConfig->addRoute('/about/sprayfire/.', array(
        'namespace' => 'SprayFire.Controller.FireController',
        'static' => true,
        'templatePath' => $Paths->getLibsPath('SprayFire', 'Responder', 'html', 'about-sprayfire.php')
    ));

}

