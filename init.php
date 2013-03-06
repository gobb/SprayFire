<?php

use \SprayFire\StdLib as SFStdLib,
    \SprayFire\Controller\FireController as FireController,
    \SprayFire\Http\FireHttp as FireHttp,
    \SprayFire\FileSys\FireFileSys as FireFileSys,
    \SprayFire\Service\FireService as FireService,
    \SprayFire\Dispatcher\FireDispatcher as FireDispatcher,
    \SprayFire\Http\Routing\FireRouting as FireRouting,
    \SprayFire\Mediator\FireMediator as FireMediator,
    \SprayFire\Responder\FireResponder as FireResponder,
    \SprayFire\Responder\Template\FireTemplate as FireTemplate,
    \SprayFire\Logging\FireLogging as FireLogging,
    \SprayFire\Plugin\FirePlugin as FirePlugin;


function startProcessing() {
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

    $getEnvironmentConfig = function() use($configPath) {
        $path = $configPath . '/SprayFire/environment.php';
        $config = array();
        if (\file_exists($path)) {
            $config = (array) include $path;
        }

        return new \SprayFire\EnvironmentConfig($config);
    };

    /** @var \SprayFire\EnvironmentConfig $EnvironmentConfig */
    $EnvironmentConfig = $getEnvironmentConfig();

    $RootPaths = new FireFileSys\RootPaths($installPath, $libsPath, $appPath, $webPath, $configPath, $logsPath);
    $Paths = new FireFileSys\Paths($RootPaths, $EnvironmentConfig->useVirtualHost());

    $ReflectionCache = new SFStdLib\ReflectionCache();
    $Container = new FireService\Container($ReflectionCache);

    $getRouteBag = function() use($Paths) {
        $path = $Paths->getConfigPath('SprayFire/routes.php');
        $Bag = include $path;
        if (!$Bag instanceof \SprayFire\Http\Routing\RouteBag) {
            $message = 'The return value from %s must be a \SprayFire\Http\Routing\RouteBag implementation.';
            \trigger_error(\sprintf($message, $path), \E_USER_NOTICE);
            $Bag = new FireRouting\RouteBag();
        }
        return $Bag;
    };

    $getPlugins = function() use($Paths, $EnvironmentConfig) {
        $path = $Paths->getConfigPath('SprayFire/plugins.php');
        $plugins = include $path;
        if (!\is_array($plugins) && !($plugins instanceof \stdClass) && !($plugins instanceof \Traversable)) {
            $message = 'The return value from %s must be a valid foreach argument.';
            \trigger_error(\sprintf($message, $path), \E_USER_NOTICE);
            $plugins = [];
        }
        return $plugins;
    };

    /**
     * We are instantiating these services here to prevent unneeded reflection for
     * components of the framework that are highly unlikely to change. This drastically
     * improves processing time and memory used.
     */

    $EmergencyLogger = new FireLogging\SysLogLogger();
    $ErrorLogger = new FireLogging\DevelopmentLogger();
    $InfoLogger = new FireLogging\DevelopmentLogger();
    $DebugLogger = new FireLogging\DevelopmentLogger();
    $LogOverseer = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);

    $Handler = new \SprayFire\Handler($LogOverseer, $EnvironmentConfig->isDevelopmentMode());
    \set_error_handler(array($Handler, 'trapError'));
    \set_exception_handler(array($Handler, 'trapException'));

    $Uri = new FireHttp\Uri();
    $Headers = new FireHttp\RequestHeaders();
    $Request = new FireHttp\Request($Uri, $Headers);

    // this is here to ensure that if we aren't using virtual host the basename
    // of the install path is removed from the URI
    $installDir = $EnvironmentConfig->useVirtualHost() ? null : \basename($Paths->getInstallPath());
    $Strategy = new FireRouting\ConfigurationMatchStrategy($installDir);

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

    $PluginInitializer = new FirePlugin\PluginInitializer($Container, $ClassLoader);
    $PluginManager = new FirePlugin\Manager($PluginInitializer, $ClassLoader);

    $services = [$Request, $ClassLoader, $Paths, $ReflectionCache, $EventRegistry,
                 $Mediator, $RoutedRequest, $OutputEscaper, $TemplateManager,
                 $LogOverseer, $EnvironmentConfig, $PluginManager];

    foreach($services as $Service) {
        $Container->addService($Service);
    }

    foreach ($EnvironmentConfig->getRegisteredEvents() as $eventName => $eventType) {
        $EventRegistry->registerEvent($eventName, $eventType);
    }

    $AppSignature = new FirePlugin\PluginSignature(
        $RoutedRequest->getAppNamespace(),
        $Paths->getAppPath(),
        $EnvironmentConfig->autoInitializeApp(),
        FirePlugin\PluginSignature::DO_INITIALIZE
    );
    $PluginManager->registerPlugin($AppSignature);

    $PluginManager->registerPlugins($getPlugins());

    $Dispatcher = new FireDispatcher\Dispatcher($Mediator, $ControllerFactory, $ResponderFactory);
    $Dispatcher->dispatchResponse($RoutedRequest);

    if ($EnvironmentConfig->isDevelopmentMode()) {
        echo '<pre>Request time ', (\microtime(true) - $requestStartTime) , ' ms</pre>';
        echo '<pre>Memory usage ', \memory_get_peak_usage(true) / 1048576, ' MB</pre>';
        echo '<pre>Errors: ', \var_dump($ErrorLogger->getLoggedMessages()), '</pre>';
        echo '<pre>Info: ', \var_dump($InfoLogger->getLoggedMessages()), '</pre>';
        echo '<pre>Debug: ', \var_dump($DebugLogger->getLoggedMessages()), '</pre>';
    }
}

\startProcessing();
