<?php

include $installPath . '/config/primary-configuration.php';

$pathGeneratorBootstrapData = array();
$pathGeneratorBootstrapData['installPath'] = $installPath;
$pathGeneratorBootstrapData['libsPath'] = $libsPath;
$pathGeneratorBootstrapData['appPath'] = $appPath;
$pathGeneratorBootstrapData['webPath'] = $webPath;
$pathGeneratorBootstrapData['configPath'] = $configPath;
$pathGeneratorBootstrapData['logsPath'] = $logsPath;

$preferredConfigObject = '\\SprayFire\\Config\\JsonConfig';
$fallbackConfigObject = '\\SprayFire\\Config\\ArrayConfig';
$configBootstrapData = array();

$configBootstrapData[0]['preferred'] = array();
$configBootstrapData[0]['fallback'] = array();
$configBootstrapData[0]['preferred']['config-object'] = $preferredConfigObject;
$configBootstrapData[0]['preferred']['config-data'] = $primaryConfigFile;
$configBootstrapData[0]['preferred']['map-key'] = 'PrimaryConfig';
$configBootstrapData[0]['fallback']['config-object'] = $fallbackConfigObject;
$configBootstrapData[0]['fallback']['config-data'] = $fallbackPrimaryConfig;
$configBootstrapData[0]['fallback']['map-key'] = 'PrimaryConfig';

$configBootstrapData[1]['preferred'] = array();
$configBootstrapData[1]['fallback'] = array();
$configBootstrapData[1]['preferred']['config-object'] = $preferredConfigObject;
$configBootstrapData[1]['preferred']['config-data'] = $routesConfigFile;
$configBootstrapData[1]['preferred']['map-key'] = 'RoutesConfig';
$configBootstrapData[1]['fallback']['config-object'] = $fallbackConfigObject;
$configBootstrapData[1]['fallback']['config-data'] = $fallbackRoutesConfig;
$configBootstrapData[1]['fallback']['map-key'] = 'RoutesConfig';

$sanityCheckBootstrapData = array();
$sanityCheckBootstrapData[0]['check-name'] = 'checkLogsPathWritable';
$sanityCheckBootstrapData[0]['fail-message'] = 'Sorry, but it appears the logs path is not writable.  Please check the permissions on your logs directory.  The currently set logs path is: <code>' . $logsPath . '</code>';
$sanityCheckBootstrapData[1]['check-name'] = 'checkSprayFireConfigExists';
$sanityCheckBootstrapData[1]['argument'] = $sprayfireConfigFile;
$sanityCheckBootstrapData[1]['fail-message'] = 'Sorry, but it appears the SprayFire configuration does not exist in its expected location; please ensure the file exists in your configs path.  Please also check that the sub-directory and filename set in primary-configuration.php are correct.';
$sanityCheckBootstrapData[2]['check-name'] = 'checkRoutesConfigExists';
$sanityCheckBootstrapData[2]['argument'] = $routesConfigFile;
$sanityCheckBootstrapData[2]['fail-message'] = 'Sorry, but it appears the routes configuration does not exist in its expected location; please ensure the file exists in your configs path.  Please also check that the sub-directory and filename set in primary-configuration.php are correct.';

$primaryBootstrapData = array();
$primaryBootstrapData['PathGeneratorBootstrap'] = $pathGeneratorBootstrapData;
$primaryBootstrapData['ConfigBootstrap'] = $configBootstrapData;
$primaryBootstrapData['SanityCheckBootstrap'] = $sanityCheckBootstrapData;

include $libsPath . '/SprayFire/Core/ClassLoader.php';

$ClassLoader = new \SprayFire\Core\ClassLoader();
\spl_autoload_register(array($ClassLoader, 'load'));
$ClassLoader->registerNamespaceDirectory('SprayFire', $libsPath);

$paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'configPath', 'webPath');
$PathGenBootstrap = new \SprayFire\Bootstrap\PathGeneratorBootstrap($paths);
$PathGenBootstrap->runBootstrap();

$Directory = $PathGenBootstrap->getPathGenerator();

$uncaughExceptionContent = $Directory->getWebPath($serverErrorContent);
$SystemLogger = new \SprayFire\Logger\SystemLogger();
$ExceptionHandler = new \SprayFire\Core\Handler\ExceptionHandler($SystemLogger, $uncaughExceptionContent, $headersFor500Response);
\set_exception_handler(array($ExceptionHandler, 'trap'));

$primaryConfigPath = $Directory->getConfigPath($primaryConfigFile);
$routesConfigPath = $Directory->getConfigPath($routesConfigFile);
$configObject = '\\SprayFire\\Config\\JsonConfig';

$configs = array();

$configs[0]['config-object'] = $configObject;
$configs[0]['config-data'] = $primaryConfigPath;
$configs[0]['map-key'] = 'PrimaryConfig';

$configs[1]['config-object'] = $configObject;
$configs[1]['config-data'] = $routesConfigPath;
$configs[1]['map-key'] = 'RoutesConfig';

// Configuration objects aren't being created properly?  Check out the info in this object to figure out why!
$ConfigErrorLog = new \SprayFire\Logger\DevelopmentLogger();
$ConfigBootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($ConfigErrorLog, $configs);
$ConfigBootstrap->runBootstrap();
$ConfigMap = $ConfigBootstrap->getConfigs();

$PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');
$RoutesConfig = $ConfigMap->getObject('RoutesConfig');

$isDevModeOn = ($PrimaryConfig->app->{'development-mode'} === 'on') ? true : false;

if ($isDevModeOn) {
    $ErrorLogger = new \SprayFire\Logger\DevelopmentLogger();
} else {
    $ErrorLogInfo = new \SplFileInfo($Directory->getLogsPath($errorLogFile));
    try {
        $ErrorLogger = new \SprayFire\Logger\FileLogger($ErrorLogInfo);
    } catch(\InvalidArgumentException $InvalArgExc) {
        $ErrorLogger = new \SprayFire\Logger\SystemLogger();
        $ErrorLogger->log(\LOG_NOTICE, 'The error file chosen could not be found.  Please check $errorLogFile in index.php');
    }
}

$ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($ErrorLogger, $isDevModeOn);
\set_error_handler(array($ErrorHandler, 'trap'));

foreach ($preBootstrapErrors as $error) {
    $ErrorHandler->trap($error['severity'], $error['message'], $error['file'], $error['line']);
}

$preBootstrapErrors = null;
$errorCallback = null;
unset($preBootstrapErrors, $errorCallback);

$Container = new \SprayFire\Core\Structure\GenericMap();
$Container->setObject('PrimaryConfig', $PrimaryConfig);
$Container->setObject('RoutesConfig', $RoutesConfig);
$Container->setObject('ErrorHandler', $ErrorHandler);
$Container->setObject('PathGenerator', $Directory);
if ($PrimaryConfig->{'development-mode'} === 'on') {
    // we are adding this object in development mode in case the error handler implementation
    // changes and does not provide access to trapped errros...this will.
    $Container->setObject('ErrorLogger', $ErrorLogger);
}

return $Container;