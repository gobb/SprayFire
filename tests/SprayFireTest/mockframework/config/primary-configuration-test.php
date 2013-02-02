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




//      DO NOT EDIT CODE BELOW THIS LINE! DO NOT EDIT CODE BELOW THIS LINE!
//      DO NOT EDIT CODE BELOW THIS LINE! DO NOT EDIT CODE BELOW THIS LINE!

