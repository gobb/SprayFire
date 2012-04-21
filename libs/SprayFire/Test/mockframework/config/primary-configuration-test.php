<?php

/*
   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   The below variables can be changed to adjust the implementation details
   of the framework's initialization process.

   Please note that changing the names of variables in this file will have horrible
   awful consequences that probably entails some things failing horribly.  Please
   do not modify any variable names, add any variables or otherwise modify this
   code outside of the values of the variables you see below.
   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

/*
   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   APP CONFIGURATION AND INI SETTINGS
   This section allows you to set the development mode for your apps and will
   determine the values for various ini configuration settings that SprayFire will
   set during bootstrapping procedures.
   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

/**
 * @brief Set to true to enable error display and other development ini settings
 *
 * @var $developmentMode
 */
$developmentMode = true;

/**
 * @brief An array of ini configuration values to set regardless of the \a $developmentMode
 * set.
 *
 * @details
 * The key of the array should be the name of the ini configuration setting.
 *
 * @var $globalIniSettings
 */
$globalIniSettings = array();
$globalIniSettings['date.timezone'] = 'America/New_York';
$globalIniSettings['default_charset'] = 'UTF-8';
$globalIniSettings['default_mimetype'] = 'text/html';
$globalIniSettings['assert.active'] = 0;
$globalIniSettings['expose_php'] = 0;

/**
 * @brief An array of ini configuraiton values to set if the \a $developmentMode \
 * is turned off.
 *
 * @details
 * The key of the array should be the name of the ini configuration setting.
 *
 * @var $productionIniSettings
 */
$productionIniSettings = array();
$productionIniSettings['display_errors'] = 0;
$productionIniSettings['display_startup_errors'] = 0;
$productionIniSettings['error_reporting'] = \E_ALL & ~\E_NOTICE;

/**
 * @brief An array of ini configuration values to set if the \a $developmentMode
 * is turned on.
 *
 * @details
 * The key of the array should be the name of the ini configuration settings.
 *
 * @var $developmentIniSettings
 */
$developmentIniSettings = array();
$developmentIniSettings['display_errors'] = 1;
$developmentIniSettings['display_startup_errors'] = 1;
$developmentIniSettings['error_reporting'] = -1;

/*
   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   CONFIGURATION FILE PATHS DETAILS
   The below variables hold the relative path to various configuration files used
   by SprayFire.  Please see SprayFire.Core.Directory to see how these variables
   are interpreted into the appropriate complete path.

   Please note that only configuration files used by SprayFire should be included
   here. If you would like to create your own app specific configuration you should
   be doing so in your app.
 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

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
$sprayFireSettingsFile = array('SprayFire', 'settings.json');

/**
 * @brief The name of the key that will be used to store \a $sprayFireConfigObject
 * in the SprayFire.Structure.ObjectMap returned by SprayFire.Bootstrap.ConfigBootstrap.
 *
 * @var $sprayFireConfigMapKey
 */
$sprayFireSettingsMapKey = 'SprayFireSettings';

/**
 * @brief Sub-directory and file name for the SprayFire routes configuration file.
 *
 * @details
 * Should exist in \a $configPath
 *
 * @var $routesConfigFile
 */
$routesConfigFile = array('SprayFire', 'routes.json');

/**
 * @brief The name of the key used to store \a $routesConfigObject in the SprayFire.Structure.ObjectMap
 * returned by SprayFire.Bootstrap.ConfigBootstrap.
 *
 * @var $routesConfigMapKey
 */
$routesConfigMapKey = 'RoutesConfig';

/*
   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   LOGGING BOOTSTRAP DETAILS

   The below configuration values define the logging objects that should be used
   for various levels of logging.






   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/


/**
 * @brief PHP or Java-style namespaced class to log emergency level messages
 *
 * @var $emergencyLoggerObject
 */
$emergencyLoggerObject = 'SprayFire.Logging.Logifier.SysLogLogger';

/**
 * @brief An array of arguments to pass to the constructor when the LoggerFactory
 * produces an \a $emergencyLoggerObject
 *
 * @var $emergencyLoggerBlueprint
 */
$emergencyLoggerBlueprint = array('SprayFire', \LOG_NDELAY, \LOG_USER);

/**
 * @brief The PHP or Java-style namespaced class used to log error level messages
 *
 * @var $errorLoggerObject
 */
$errorLoggerObject = 'SprayFire.Logging.Logifier.ErrorLogLogger';

/**
 * @brief An array of arguments to pass to the constructor when the LoggerFactory
 * produces an \a $errorLoggerObject
 *
 * @var $errorLoggerBlueprint
 */
$errorLoggerBlueprint = array();

/**
 * @brief PHP or Java-style namespaced class used to log debug level messages
 *
 * @var $debugLoggerObject
 */
$debugLoggerObject = 'SprayFire.Logging.Logifier.FileLogger';

/**
 * @brief An array of constructor arguments passed to \a $debugLoggerObject when
 * LoggerFactory produces it.
 *
 * @var $debugLoggerBlueprint
 */
$debugLoggerBlueprint = array('sprayfire-debug.txt');

/**
 * @brief PHP or Java-style namespaced class used to log info level messages
 *
 * @var $infoLoggerObject
 */
$infoLoggerObject = 'SprayFire.Logging.Logifier.FileLogger';

/**
 * @brief An array of constructor arguments passed to \a $infoLoggerObject when
 * produced by LoggerFactory.
 *
 * @var $infoLogFile
 */
$infoLoggerBlueprint = array('sprayfire-info.txt');

//      DO NOT EDIT CODE BELOW THIS LINE! DO NOT EDIT CODE BELOW THIS LINE!
//      DO NOT EDIT CODE BELOW THIS LINE! DO NOT EDIT CODE BELOW THIS LINE!

$iniSettings = array();
$iniSettings['global'] = $globalIniSettings;
$iniSettings['production'] = $productionIniSettings;
$iniSettings['development'] = $developmentIniSettings;

$sprayFireConfig = array();
$sprayFireConfig['file'] = $sprayFireSettingsFile;
$sprayFireConfig['map-key'] = $sprayFireSettingsMapKey;
$routesConfig = array();
$routesConfig['file'] = $routesConfigFile;
$routesConfig['map-key'] = $routesConfigMapKey;
$configData = array();
$configData['sprayFireConfig'] = $sprayFireConfig;
$configData['routesConfig'] = $routesConfig;

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