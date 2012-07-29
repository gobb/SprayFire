<?php

/**
 * @file
 * @brief The primary intialization script for SprayFire
 */

\session_start();

$requestStartTime = \microtime(true);

$developmentMode = true;

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// PREBOOTSTRAP ERROR & EXCEPTION HANDLING
// This is just a basic error handler that stores error information in an array.
// If /config/sprayfire-configuration.json is setup to have the ErrorHandler
// bootstrap ran this error handler will be replaced by
// SprayFire.Core.Handler.ErrorHandler::trap().  If not it will be your responsibility
// to replace this error handler.  If the ErrorHandler is appropriately set this
// function will be unset; the \a $preBootstrapErrors will be merged into the error
// handler and they will be unset as well.  If the appropriate class based error
// handler is not setup then this function will serve as the primary error handler
// for processing the request
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$preBootstrapErrors = array();
$errorCallback = function($severity, $message, $file = null, $line = null, $context = null) use (&$preBootstrapErrors) {

    $normalizeSeverity = function() use ($severity) {
        $severityMap = array(
            E_WARNING => 'E_WARNING',
            E_NOTICE => 'E_NOTICE',
            E_STRICT => 'E_STRICT',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED'
        );
        if (\array_key_exists($severity, $severityMap)) {
            return $severityMap[$severity];
        }
        return 'E_UNKOWN_SEVERITY';
    };

    $index = \count($preBootstrapErrors);
    $preBootstrapErrors[$index]['timestamp'] = \date('M-d-Y H:i:s');
    $preBootstrapErrors[$index]['severity'] = $normalizeSeverity();
    $preBootstrapErrors[$index]['message'] = $message;
    $preBootstrapErrors[$index]['file'] = $file;
    $preBootstrapErrors[$index]['line'] = $line;

    // here to return an error if improper type hints are passed
    $unhandledSeverity = array(E_RECOVERABLE_ERROR);
    if (\in_array($severity, $unhandledSeverity)) {
        return false;
    }
};
\set_error_handler($errorCallback);

$exceptionCallback = function(\Exception $Exception) use ($developmentMode) {
    // there's nothing really we can do here at this point besides error_log the
    // message.  We'll send a 500 http response and spit back some basic fubar
    // response HTML.
    // TODO: Make this a prettier message?  Perhaps with some basic styling?
    if (!$developmentMode) {
        \error_log($Exception->getMessage());
    } else {
        \var_dump($Exception);
    }
    \header('HTTP/1.1 500 Internal Server Error');
    \header('Content-Type: text-html; charset=UTF-8');

    echo <<< HTML
<!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Server Error</title>
        </head>
        <body>
            <h1>500 Internal Server Error</h1>
        </body>
    </html>
HTML;

    exit;
};
\set_exception_handler($exceptionCallback);

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

$EmergencyLogger = new \SprayFire\Logging\FireLogging\SysLogLogger();
$ErrorLogger = new \SprayFire\Logging\FireLogging\ErrorLogLogger();
$DebugLogger = $InfoLogger = new \SprayFire\Logging\NullLogger();
$LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);

$Uri = new \SprayFire\Http\FireHttp\ResourceIdentifier();
$RequestHeaders = new \SprayFire\Http\FireHttp\RequestHeaders();
$Request = new \SprayFire\Http\FireHttp\Request($Uri, $RequestHeaders);

$Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
$routesConfig = $configPath . '/SprayFire/routes.json';
$installDir = \basename($installPath);
$Router = new \SprayFire\Http\Routing\FireRouting\Router($Normalizer, $Paths, $routesConfig, $installDir);

$RoutedRequest = $Router->getRoutedRequest($Request);

$ReflectionCache = new \Artax\ReflectionCacher();
$Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
$JavaNameConverter = new \SprayFire\JavaNamespaceConverter();

$ControllerFactory = new \SprayFire\Controller\FireController\Factory($ReflectionCache, $Container, $LogDelegator, $JavaNameConverter);
$ResponderFactory = new \SprayFire\Responder\Factory($ReflectionCache, $Container, $LogDelegator, $JavaNameConverter);

$Container->addService($LogDelegator);
$Container->addService($Paths);
$Container->addService($ReflectionCache);
$Container->addService($ClassLoader);
$Container->addService($Request);
$Container->addService($JavaNameConverter);
$Container->addService($RoutedRequest);

$Dispatcher = new \SprayFire\Dispatcher\FireDispatcher($Router, $LogDelegator, $ControllerFactory, $ResponderFactory);
$Dispatcher->dispatchResponse($Request);

echo (microtime(true) - $requestStartTime);
\var_dump(memory_get_peak_usage(true));
\var_dump($preBootstrapErrors);
$stdClass = new \stdClass();
\var_dump($stdClass);