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
$configBootstrapData[0]['preferred']['config-data'] = $sprayFireConfigFile;
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
$sanityCheckBootstrapData[0]['argument'] = $logsPath;
$sanityCheckBootstrapData[0]['fail-message'] = 'Sorry, but it appears the logs path is not writable.  Please check the permissions on your logs directory.  The currently set logs path is: <code>' . $logsPath . '</code>';
$sanityCheckBootstrapData[1]['check-name'] = 'checkSprayFireConfigExists';
$sanityCheckBootstrapData[1]['argument'] = $sprayFireConfigFile;
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



return $Container;