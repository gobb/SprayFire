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

$RootPaths = new \SprayFire\FileSys\RootPaths($installPath, $libsPath, $appPath, $webPath, $configPath, $logsPath);
$Paths = new \SprayFire\FileSys\Paths($RootPaths);

$EmergencyLogger = new \SprayFire\Logging\Logifier\SysLogLogger();
$ErrorLogger = new \SprayFire\Logging\Logifier\ErrorLogLogger();
$DebugLogger = new \SprayFire\Logging\Logifier\NullLogger();
$InfoLogger = new \SprayFire\Logging\Logifier\NullLogger();
$LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);

$Uri = new \SprayFire\Http\ResourceIdentifier();
$RequestHeaders = new \SprayFire\Http\StandardRequestHeaders();
$Request = new \SprayFire\Http\StandardRequest($Uri, $RequestHeaders);

$Normalizer = new \SprayFire\Http\Routing\Normalizer();
$routesConfig = $configPath . '/SprayFire/routes.json';
$installDir = \basename($installPath);
$Router = new \SprayFire\Http\Routing\StandardRouter($Normalizer, $Paths, $routesConfig, $installDir);

$ReflectionCache = new \Artax\ReflectionCacher();
$Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);

$controllerFactoryName = 'SprayFire.Controller.Factory';
$controllerFactoryCallback = function() use ($ReflectionCache, $Container, $LogDelegator) {
    return array($ReflectionCache, $Container, $LogDelegator);
};

$Container->addService($LogDelegator);
$Container->addService($Paths);
$Container->addService($ReflectionCache);
$Container->addService($ClassLoader);
$Container->addService($Request);
$Container->addService($controllerFactoryName, $controllerFactoryCallback);
$Container->addService('SprayFire.JavaNamespaceConverter');

$Factory = $Container->getService('SprayFire.Controller.Factory');
$RoutedRequest = $Router->getRoutedRequest($Request);
$controllerName = $RoutedRequest->getController();
$action = $RoutedRequest->getAction();
$parameters = $RoutedRequest->getParameters();
$Controller = $Factory->makeObject($controllerName);
$Controller->giveDirtyData(array('action' => $action));
$Controller->$action($parameters);

$Responder = new \SprayFire\Responder\HtmlResponder();
$response = $Responder->generateDynamicResponse($Controller);
echo $response;

echo '<pre>', \print_r($preBootstrapErrors, true), '</pre>';
\var_dump(memory_get_peak_usage());
$class = new \stdClass();
\var_dump($class);
