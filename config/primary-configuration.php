<?php

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// The below variables can be changed to adjust the implementation details
// of the framework's initialization process.
//
// Please note that changing the names of variables in this file will have horrible
// awful consequences that probably entails some things failing horribly.  Please
// do not modify any variable names, add any variables or otherwise modify this
// code outside of the values of the variables you see below.
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// APP CONFIGURATION AND INI SETTINGS
// This section allows you to set the development mode for your apps and will
// determine the values for various ini configuration settings that SprayFire will
// set during bootstrapping procedures.
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

/**
 * @var $developmentMode Set to true to enable error display and other development
 *      ini settings
 */
$developmentMode = true;

/**
 * @brief The key of the array should be the name of the ini configuration setting.
 *
 * @var $globalIniSettings An array of ini configuration values to set regardless
 *      of the \a $developmentMode set.
 */
$globalIniSettings = array();
$globalIniSettings['allow_url_fopen'] = 0;
$globalIniSettings['allow_url_include'] = 0;
$globalIniSettings['asp_tags'] = 0;
$globalIniSettings['date.timezone'] = 'America/New_York';
$globalIniSettings['default_charset'] = 'UTF-8';
$globalIniSettings['default_mimetype'] = 'text/html';
$globalIniSettings['assert.active'] = 0;
$globalIniSettings['magic_quotes_gpc'] = 0;
$globalIniSettings['magic_quotes_runtime'] = 0;
$globalIniSettings['expose_php'] = 0;

/**
 * @brief The key of the array should be the name of the ini configuration setting.
 *
 * @var $productionIniSettings An array of ini configuraiton values to set if the
 *      \a $developmentMode is turned off.
 */
$productionIniSettings = array();
$productionIniSettings['display_errors'] = 0;
$productionIniSettings['display_startup_errors'] = 0;
$productionIniSettings['error_reporting'] = \E_ALL & ~\E_NOTICE;

/**
 * @brief The key of the array should be the name of the ini configuration settings.
 *
 * @var $developmentIniSettings An array of ini configuration values to set if the
 *      \a $developmentMode is turned on.
 */
$developmentIniSettings = array();
$developmentIniSettings['display_errors'] = 1;
$developmentIniSettings['display_startup_errors'] = 1;
$developmentIniSettings['error_reporting'] = -1;

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// CONFIGURATION BOOTSTRAP DETAILS
// The below variables hold the information needed by the LoggerBootstrap to
// properly create the appropriate configuration objects needed to get SprayFire
// initialized.  It is important to note that these are only configuration files
// needed by SprayFire, adding to these values will not influence the configuration
// objects available to your app.  Configuration objects you would like in your
// app should be returned by your app's own `Bootstrapper::runBootstrap()`.
//
// For more information about the
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

/**
 * @brief A PHP or Java-style namespaced interface that configuration objects should
 * implement.
 *
 * @details
 * While this should generally be an interface an abstract or concrete class type
 * may be set.  However setting to a more concrete implementation will ruin any kind
 * of polymorphism that is possible with an interface.  Either way, do not change
 * the name of this variable.
 *
 * @var $configInterface
 * @see SprayFire.Bootstrap.ConfigBootstrap
 */
$configInterface = 'SprayFire.Config.Configuration';

/**
 * @brief This file should be located in the \a $configPath
 *
 * @details
 * See the linked wiki page for how this array is converted into a sub-directory
 * structure by SprayFire.
 *
 * @var $sprayfireConfigFile
 * @see http://github.com/cspray/SprayFire/wiki/Directory-Structure
 */
$sprayFireConfigFile = array('json', 'sprayfire-configuration.json');

/**
 * @var $sprayFireConfigObject PHP or Java-style class name
 */
$sprayFireConfigObject = 'SprayFire.Config.JsonConfig';

/**
 * @var $sprayFireConfigMapKey The name of the key that this object will be stored
 *      in the container map.
 */
$sprayFireConfigMapKey = 'SprayFireConfig';

/**
 * @brief This file should be located in the \a $configPath
 *
 * @var $routesConfigFile sub-directory and file name for the SprayFire routes configuration file
 */
$routesConfigFile = array('json', 'routes.json');

$routesConfigObject = 'SprayFire.Config.JsonConfig';

$routesConfigMapKey = 'RoutesConfig';

/**
 * @brief This file should be located in the \a $configPath
 *
 * @var $pluginsConfigFile sub-directory and file name for plugins loaded in this app
 */
$pluginsConfigFile = array('json', 'plugins.json');

$pluginsConfigObject = 'SprayFire.Config.JsonConfig';

$pluginsConfigMapKey = 'PluginsConfig';

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// CONFIGURATION FILE PATHS DETAILS
// The below variables hold the relative path to various configuration files used
// by SprayFire.  Please see SprayFire.Core.Directory to see how these variables
// are interpreted into the appropriate complete path.
//
// Please note that only configuration files used by SprayFire should be included
// here. If you would like to create your own app specific configuration you should
// be doing so in your app.
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

/**
 * @brief Note that you may pass the name of this class in either Java or PHP style.
 *
 * @var $emergencyLoggerObject The name of the class responsible for logging emergency
 *      level messages
 */
$emergencyLoggerObject = 'SprayFire.Logging.Logifier.SysLogLogger';

$emergencyLoggerBlueprint = array('SprayFire', \LOG_NDELAY, \LOG_USER);

/**
 * @brief Note that you may pass the name of this class in either Java or PHP style.
 *
 * @var $errorLoggerObject The name of the class responsible for logging error level
 *      messages
 */
$errorLoggerObject = 'SprayFire.Logging.Logifier.ErrorLogLogger';

$errorLoggerBlueprint = array();

/**
 * @brief Note that you may pass the name of this class in either Java or PHP style.
 *
 * @var $debugLoggerObject The name of the class responsible for logging debug level
 *      messages
 */
$debugLoggerObject = 'SprayFire.Logging.Logifier.DebugLogger';

$debugLoggerBlueprint = array('sprayfire-debug.txt');

/**
 * @brief Note that you may pass the name of this class in either Java or PHP style.
 *
 * @var $infoLoggerObject The name of the class responsible for logging info level
 *      messages
 */
$infoLoggerObject = 'SprayFire.Logging.Logifier.FileLogger';

/**
 * @var $infoLogFile The sub-directory and file that \a $infoLoggerObject should
 *      store info in.
 */
$infoLoggerBlueprint = array('sprayfire-info.txt');

//      DO NOT EDIT CODE BELOW THIS LINE! DO NOT EDIT CODE BELOW THIS LINE!
//      DO NOT EDIT CODE BELOW THIS LINE! DO NOT EDIT CODE BELOW THIS LINE!

$iniSettings = array();
$iniSettings['global'] = $globalIniSettings;
$iniSettings['production'] = $productionIniSettings;
$iniSettings['development'] = $developmentIniSettings;

$sprayFireConfig = array();
$sprayFireConfig['data'] = $sprayFireConfigFile;
$sprayFireConfig['object'] = $sprayFireConfigObject;
$sprayFireConfig['map-key'] = $sprayFireConfigMapKey;
$routesConfig = array();
$routesConfig['data'] = $routesConfigFile;
$routesConfig['object'] = $routesConfigObject;
$routesConfig['map-key'] = $routesConfigMapKey;
$pluginsConfig = array();
$pluginsConfig['data'] = $pluginsConfigFile;
$pluginsConfig['object'] = $pluginsConfigObject;
$pluginsConfig['map-key'] = $pluginsConfigMapKey;
$configData = array();
$configData['interface'] = $configInterface;
$configData['sprayFireConfig'] = $sprayFireConfig;
$configData['routesConfig'] = $routesConfig;
$configData['pluginsConfig'] = $pluginsConfig;

$loggerData = array();
$loggerData['emergency'] = array();
$loggerData['emergency']['object'] = $emergencyLoggerObject;
$loggerData['emergency']['blueprint'] = $emergencyLoggerBlueprint;
$loggerData['error'] = array();
$loggerData['error']['object'] = $errorLoggerObject;
$loggerData['error']['blueprint'] = $errorLoggerBlueprint;
$loggerData['debug'] = array();
$loggerData['debug']['object'] = $debugLoggerObject;
$loggerData['debug']['blueprint'] = $debugLoggerBlueprint;
$loggerData['info'] = array();
$loggerData['info']['object'] = $infoLoggerObject;
$loggerData['info']['blueprint'] = $infoLoggerBlueprint;

return \compact('developmentMode', 'iniSettings', 'configData', 'loggerData');