<?php

$startTime = \microtime(true);

$errors = array();

$errorCallback = function($severity, $message, $file = null, $line = null, $context = null) use (&$errors) {

    $normalizeSeverity = function() use ($severity) {
        $severityMap = array(
            E_WARNING => 'E_WARNING',
            E_NOTICE => 'E_NOTICE',
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

    $index = \count($errors);
    $errors[$index]['severity'] = $normalizeSeverity();
    $errors[$index]['message'] = $message;
    $errors[$index]['file'] = $file;
    $errors[$index]['line'] = $line;

    // here to return an error if improper type hints are passed
    $unhandledSeverity = array(E_RECOVERABLE_ERROR);
    if (\in_array($severity, $unhandledSeverity)) {
        return false;
    }

};

\set_error_handler($errorCallback);

include $libsPath . '/SprayFire/Core/ClassLoader.php';

$ClassLoader = new \SprayFire\Core\ClassLoader();
\spl_autoload_register(array($ClassLoader, 'load'));
$ClassLoader->registerNamespaceDirectory('SprayFire', $libsPath);

$paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'configPath', 'webPath');
$PathGenBootstrap = new \SprayFire\Bootstrap\PathGeneratorBootstrap($paths);
$PathGenBootstrap->runBootstrap();

$Directory = $PathGenBootstrap->getPathGenerator();

$uncaughExceptionContent = $Directory->getWebPath('500.html');
$SystemLogger = new \SprayFire\Logger\SystemLogger();
$ExceptionHandler = new \SprayFire\Core\Handler\ExceptionHandler($SystemLogger, $uncaughExceptionContent);
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

$ConfigErrorLog = new \SprayFire\Logger\DevelopmentLogger();
$ConfigBootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($ConfigErrorLog, $configs);
$ConfigBootstrap->runBootstrap();
$ConfigMap = $ConfigBootstrap->getConfigs();

$PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');
$RoutesConfig = $ConfigMap->getObject('RoutesConfig');

$configValid = function() use ($PrimaryConfig, $RoutesConfig) {
    if (!isset($PrimaryConfig) || !isset($RoutesConfig)) {
        return false;
    }
    return true;
};

if (!$configValid()) {
    throw new \SprayFire\Exception\FatalRuntimeException('A required configuration object could not be found.  Please ensure you have a configuration and routes file in your config path.');
}

if ($PrimaryConfig->app->{'development-mode'} === 'on') {
    $ErrorLogger = new \SprayFire\Logger\DevelopmentLogger();
} else {
    $ErrorLogInfo = new \SplFileInfo($Directory->getLogsPath($errorLogFile));
    try {
        $ErrorLogger = new \SprayFire\Logger\FileLogger($ErrorLogInfo);
    } catch(InvalidArgumentException $InvalArgExc) {
        $ErrorLogger = new \SprayFire\Logger\SystemLogger();
        $ErrorLogger->log(\date('M-d-Y H:i:s'), 'The error file chosen could not be found.  Please check $errorLogFile in index.php');
    }
}

$ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($ErrorLogger);
\set_error_handler(array($ErrorHandler, 'trap'));

$Container = new \SprayFire\Core\Structure\GenericMap();
$Container->setObject('PrimaryConfig', $PrimaryConfig);
$Container->setObject('RoutesConfig', $RoutesConfig);

return $Container;