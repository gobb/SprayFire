<?php

use \SprayFire\FileSys\FireFileSys as FireFileSys,
    \SprayFire\Service\FireService as FireService,
    \SprayFire\Dispatcher\FireDispatcher as FireDispatcher,
    \SprayFire\Http\FireHttp as FireHttp,
    \SprayFire\Http\Routing\FireRouting as FireRouting,
    \SprayFire\Mediator\FireMediator as FireMediator,
    \SprayFire\Controller\FireController as FireController,
    \SprayFire\Responder\FireResponder as FireResponder,
    \SprayFire\Responder\Template\FireTemplate as FireTemplate,
    \SprayFire\Logging\FireLogging as FireLogging,
    \SprayFire\Utils as SFUtils;

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
$ClassLoader->registerNamespaceDirectory('Zend', $libsPath);
$ClassLoader->setAutoloader();

$RootPaths = new FireFileSys\RootPaths($installPath, $libsPath, $appPath, $webPath, $configPath, $logsPath);
$Paths = new FireFileSys\Paths($RootPaths);

$JavaNameConverter = new SFUtils\JavaNamespaceConverter();
$ReflectionCache = new SFUtils\ReflectionCache($JavaNameConverter);
$Container = new FireService\Container($ReflectionCache);

$getEnvironmentConfig = function() use($Paths) {
    $path = $Paths->getConfigPath('SprayFire', 'environment.php');
    $config = array();
    if (\file_exists($path)) {
        $config = (array) include $path;
    }

    return new \SprayFire\EnvironmentConfig($config);
};

$getRouteBag = function() use ($Paths) {
    $Bag = include $Paths->getConfigPath('SprayFire', 'routes.php');
    if (!$Bag instanceof \SprayFire\Http\Routing\RouteBag) {
        $Bag = new FireRouting\RouteBag();
    }
    return $Bag;
};

/**
 * We are instantiating these services here to prevent unneeded reflection for
 * components of the framework that are highly unlikely to change. This drastically
 * improves processing time and memory used.
 */

$EnvironmentConfig = $getEnvironmentConfig();

$EmergencyLogger = new FireLogging\SysLogLogger();
$ErrorLogger = new FireLogging\ErrorLogLogger();
$InfoLogger = new FireLogging\DevelopmentLogger();
$DebugLogger = new FireLogging\DevelopmentLogger();
$LogOverseer = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);

$Handler = new \SprayFire\Handler($LogOverseer, $EnvironmentConfig->isDevelopmentMode());
\set_error_handler(array($Handler, 'trapError'));
\set_exception_handler(array($Handler, 'trapException'));

$Uri = new FireHttp\Uri();
$Headers = new FireHttp\RequestHeaders();
$Request = new FireHttp\Request($Uri, $Headers);

$Strategy = new FireRouting\ConfigurationMatchStrategy(\basename($Paths->getInstallPath()));
$RouteBag = $getRouteBag();
$Normalizer = new FireRouting\Normalizer();
$Router = new FireRouting\Router($Strategy, $RouteBag, $Normalizer);
$RoutedRequest = $Router->getRoutedRequest($Request);

$CallbackStorage = new FireMediator\CallbackStorage();
$EventRegistry = new FireMediator\EventRegistry($CallbackStorage);
$Mediator = new FireMediator\Mediator($EventRegistry, $CallbackStorage);

$OutputEscaper = new FireResponder\OutputEscaper($EnvironmentConfig->getDefaultCharset());
$TemplateManager = new FireTemplate\Manager();

$ControllerFactory = new FireController\Factory($ReflectionCache, $Container, $LogOverseer);
$ResponderFactory = new FireResponder\Factory($ReflectionCache, $Container, $LogOverseer);

$Container->addService($Request);
$Container->addService($ClassLoader);
$Container->addService($Paths);
$Container->addService($JavaNameConverter);
$Container->addService($ReflectionCache);
$Container->addService($EventRegistry);
$Container->addService($Mediator);
$Container->addService($RouteBag);
$Container->addService($Router);
$Container->addService($RoutedRequest);
$Container->addService($OutputEscaper);
$Container->addService($TemplateManager);
$Container->addService($LogOverseer);
$Container->addService($ControllerFactory);
$Container->addService($ResponderFactory);
$Container->addService($EnvironmentConfig);

foreach ($EnvironmentConfig->getRegisteredEvents() as $eventName => $eventType) {
    $EventRegistry->registerEvent($eventName, $eventType);
}

$AppInitializer = new FireDispatcher\AppInitializer($Container, $Paths, $ClassLoader);
$AppInitializer->initializeApp($RoutedRequest);

$Dispatcher = new FireDispatcher\Dispatcher($Router, $Mediator, $ControllerFactory, $ResponderFactory);
$Dispatcher->dispatchResponse($Request);

if ($EnvironmentConfig->isDevelopmentMode()) {
    echo '<pre>Request time ' . (\microtime(true) - $requestStartTime) . '</pre>';
    \var_dump(\memory_get_peak_usage(true));
    \var_dump(new \stdClass());
}
