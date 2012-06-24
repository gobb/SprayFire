<?php

/**
 * @file
 * @brief The primary intialization script for SprayFire
 */

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
    if ($developmentMode) {
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

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// DIRECTORY PATH CONFIGURATION DETAILS
//
// NO TRAILING SLASHES ON DIRECTORIES! NO TRAILING SLASHES ON DIRECTORIES!
//
// $installPath is defined in /web/index.php as the complete path to the app
// install directory.  The install directory should hold, at minimum, the 'libs',
// 'app' and 'web' folders.
//
// It is highly recommended that you do not change the libs directory or the
// configs directory, critical aspects of SprayFire depend on files in these
// directories
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

$installPath = __DIR__;
$libsPath = $installPath . '/libs';
$configPath = $installPath . '/config';
$appPath = $installPath . '/app';
$logsPath = $installPath . '/logs';
$webPath = $installPath . '/web';

$debugFilePath = $logsPath . '/sprayfire-debug.txt';
$infoFilePath = $logsPath .'/sprayfire-info.txt';

include $libsPath . '/ClassLoader/Loader.php';
$ClassLoader = new \ClassLoader\Loader();
$ClassLoader->registerNamespaceDirectory('SprayFire', $libsPath);
$ClassLoader->registerNamespaceDirectory('Artax', $libsPath . '/Artax/src');
$ClassLoader->setAutoloader();

$ReflectionCache = new \Artax\ReflectionPool();
$Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);

$Container->addService('SprayFire.FileSys.Paths', function() use($installPath,
                                                                  $libsPath,
                                                                  $configPath,
                                                                  $appPath,
                                                                  $logsPath,
                                                                  $webPath) {
    $arg = array();
    $arg['install'] = $installPath;
    $arg['lib'] = $libsPath;
    $arg['config'] = $configPath;
    $arg['app'] = $appPath;
    $arg['logs'] = $logsPath;
    $arg['web'] = $webPath;
    return array($arg);
});
$Container->addService($ReflectionCache, function() {});
$Container->addService('SprayFire.Logging.Logifier.LogDelegator', function() use ($debugFilePath,
                                                                                  $infoFilePath) {
    $DebugFile = new \SplFileInfo($debugFilePath);
    $InfoFile = new \SplFileInfo($infoFilePath);
    $Emergency = new \SprayFire\Logging\Logifier\SysLogLogger();
    $Error = new \SprayFire\Logging\Logifier\ErrorLogLogger();
    $Debug = new \SprayFire\Logging\Logifier\FileLogger($DebugFile);
    $Info = new \SprayFire\Logging\Logifier\FileLogger($InfoFile);
    return array($Emergency, $Error, $Debug, $Info);
});
$Container->addService('SprayFire.Routing.Normalizer', function() {});

/**
 * @todo The following markup eventually needs to be moved into the default template for HtmlResponder.
 */

// NOTE: The below code is a temporary measure until the templating system is in place

$styleCss = $Container->getService('SprayFire.FileSys.Paths')->getUrlPath('css', 'sprayfire.style.css');
$sprayFireLogo = $Container->getService('SprayFire.FileSys.Paths')->getUrlPath('images', 'sprayfire-logo-bar-75.png');

echo <<<HTML
<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <link href="{$styleCss}" rel="stylesheet" type="text/css" />
        </head>
        <body>
            <div id="content">
                <div id="header">
                    <h1><img src="{$sprayFireLogo}" id="sprayfire-logo" alt="SprayFire logo" width="200" height="75" /></h1>
                    <ul>
                        <li>ver: {''}</li>
                        <li>repo: <a href="http://www.github.com/cspray/SprayFire">http://www.github.com/cspray/SprayFire/</a></li>
                        <li>wiki: <a href="http://www.github.com/cspray/SprayFire/wiki/">http://www.github.com/cspray/SprayFire/wiki/</a></li>
                        <li>api docs: coming soon!</li>

                    </ul>
                </div>

                <div id="body">
                    <div id="main-content">
                    </div>
                </div>

                <div id="footer">
                    <p style="text-align:center;"><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> &copy; Charles Sprayberry 2011</p>
                </div>
            </div>
HTML;


    $errors = 'Errors: ' . \print_r($preBootstrapErrors, true);
    $memUsage = 'Memory usage: ' . \memory_get_peak_usage() / (1000*1024) . ' mb';
    $runTime = 'Execution time: ' . (\microtime(true) - $requestStartTime) . ' seconds';
    $debugInfo = <<<HTML
            <div id="debug-info" style="margin-top:1em;border:2px solid black;padding:5px;font-family:monospace;">
                <ul>
                    <li><pre>$errors</pre></li>
                    <li>$memUsage</li>
                    <li>$runTime</li>
                </ul>
            </div>
HTML;

echo <<<HTML
    {$debugInfo}
    </body>
</html>
HTML;

