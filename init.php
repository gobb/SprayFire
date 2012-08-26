<?php

/**
 * @file
 * @brief The primary intialization script for SprayFire
 */

\session_start();

$requestStartTime = \microtime(true);

$installPath = __DIR__;
$libsPath = $installPath . '/libs';
$appPath = $installPath . '/app';
$webPath = $installPath . '/web';
$configPath = $installPath . '/config';
$logsPath = $installPath . '/logs';

include $libsPath . '/ClassLoader/Loader.php';
$ClassLoader = new \ClassLoader\Loader();
$ClassLoader->registerNamespaceDirectory('SprayFire', $libsPath);
$ClassLoader->registerNamespaceDirectory('Artax', $libsPath . '/Artax/src');
$ClassLoader->setAutoloader();

$RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($installPath, $libsPath, $appPath, $webPath, $configPath, $logsPath);
$Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);

$JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
$ReflectionCache = new \SprayFire\ReflectionCache($JavaNameConverter);
$Container = new \SprayFire\Service\FireService\Container($ReflectionCache);

$getEnvironmentConfig = function() use ($Paths, $ReflectionCache, $Container) {
    return include $Paths->getConfigPath('SprayFire', 'environment.php');
};

$Container->addService($ClassLoader);
$Container->addService($Paths);
$Container->addService($JavaNameConverter);

$environmentConfig = $getEnvironmentConfig();

foreach ($environmentConfig['services'] as $service) {
    $Container->addService($service['name'], $service['parameterCallback']);
}

$Handler = $Container->getService($environmentConfig['services']['Handler']['name']);

\set_error_handler(array($Handler, 'trapError'));
\set_exception_handler(array($Handler, 'trapException'));

$Request = $Container->getService($environmentConfig['services']['HttpRequest']['name']);
$Router = $Container->getService($environmentConfig['services']['HttpRouter']['name']);
$ControllerFactory = $Container->getService($environmentConfig['services']['ControllerFactory']['name']);
$ResponderFactory = $Container->getService($environmentConfig['services']['ResponderFactory']['name']);

$Container->addService($Router->getRoutedRequest($Request));

$AppInitializer = new \SprayFire\Dispatcher\FireDispatcher\AppInitializer($Container, $ClassLoader, $Paths);
$Mediator = new \SprayFire\Mediator\FireMediator\Mediator();

$Dispatcher = new \SprayFire\Dispatcher\FireDispatcher\Dispatcher($Router, $AppInitializer, $ControllerFactory, $ResponderFactory);
$Dispatcher->dispatchResponse($Request);

echo '<pre>Request time ' . (\microtime(true) - $requestStartTime) . '</pre>';
\var_dump(\memory_get_peak_usage(true));