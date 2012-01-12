<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Bootstrap;

/**
 * @brief
 */
class BootstrapDataTest extends \PHPUnit_Framework_TestCase {

    public function testBootstrapData() {
        $installPath = \SPRAYFIRE_ROOT;
        $libsPath = $installPath . '/libs';
        $configPath = $installPath . '/config';
        $appPath = $installPath . '/app';
        $logsPath = $installPath . '/logs';
        $webPath = $installPath . '/web';
        $paths = \compact('installPath', 'libsPath', 'configPath', 'appPath', 'logsPath', 'webPath');

        $primaryConfig = $libsPath . '/SprayFire/Test/mockframework/config/primary-configuration-test.php';

        $BootstrapData = new \SprayFire\Bootstrap\BootstrapData($primaryConfig, $paths);

        $expectedDirectoryBootstrapData = $paths;
        $actualDirectoryBootstrapData = $BootstrapData->PathGenBootstrap;

        $expectedIniBootstrapData = array();
        $expectedIniBootstrapData['allow_url_fopen'] = 0;
        $expectedIniBootstrapData['allow_url_include'] = 0;
        $expectedIniBootstrapData['asp_tags'] = 0;
        $expectedIniBootstrapData['date.timezone'] = 'America/New_York';
        $expectedIniBootstrapData['default_charset'] = 'UTF-8';
        $expectedIniBootstrapData['default_mimetype'] = 'text/html';
        $expectedIniBootstrapData['assert.active'] = 0;
        $expectedIniBootstrapData['magic_quotes_gpc'] = 0;
        $expectedIniBootstrapData['magic_quotes_runtime'] = 0;
        $expectedIniBootstrapData['expose_php'] = 0;
        $expectedIniBootstrapData['display_errors'] = 1;
        $expectedIniBootstrapData['display_startup_errors'] = 1;
        $expectedIniBootstrapData['error_reporting'] = -1;
        $actualIniBootstrapData = $BootstrapData->IniBootstrap;

        $expectedConfigBootstrapData = array();
        $expectedConfigBootstrapData['sprayFireConfig']['file'] = $configPath . '/json/sprayfire-configuration.json';
        $expectedConfigBootstrapData['sprayFireConfig']['object'] = 'SprayFire.Config.JsonConfig';
        $expectedConfigBootstrapData['sprayFireConfig']['map-key'] = 'SprayFireConfig';
        $expectedConfigBootstrapData['routesConfig']['file'] = $configPath . '/json/routes.json';
        $expectedConfigBootstrapData['routesConfig']['object'] = 'SprayFire.Config.JsonConfig';
        $expectedConfigBootstrapData['routesConfig']['map-key'] = 'RoutesConfig';
        $expectedConfigBootstrapData['pluginsConfig']['file'] = $configPath . '/json/plugins.json';
        $expectedConfigBootstrapData['pluginsConfig']['object'] = 'SprayFire.Config.JsonConfig';
        $expectedConfigBootstrapData['pluginsConfig']['map-key'] = 'PluginsConfig';
        $actualConfigBootstrapData = $BootstrapData->ConfigBootstrap;

        $expectedLoggingBootstrapData = array();
        $expectedLoggingBootstrapData['emergency'] = array();
        $expectedLoggingBootstrapData['emergency']['object'] = 'SprayFire.Logging.Logifier.SysLogLogger';
        $expectedLoggingBootstrapData['emergency']['blueprint'] = array('SprayFire', \LOG_NDELAY, \LOG_LOCAL2);
        $expectedLoggingBootstrapData['error'] = array();
        $expectedLoggingBootstrapData['error']['object'] = 'SprayFire.Logging.Logifier.ErrorLogLogger';
        $expectedLoggingBootstrapData['error']['blueprint'] = array();
        $expectedLoggingBootstrapData['debug'] = array();
        $expectedLoggingBootstrapData['debug']['object'] = 'SprayFire.Logging.Logifier.DebugLogger';
        $expectedLoggingBootstrapData['debug']['blueprint'] = array('sprayfire-debug.txt');
        $expectedLoggingBootstrapData['info']['object'] = 'SprayFire.Logging.Logifier.FileLogger';
        $expectedLoggingBootstrapData['info']['blueprint'] = array('sprayfire-info.txt');
        $actualLoggingBootstrapData = $BootstrapData->LoggingBootstrap;

        $this->assertSame($expectedConfigBootstrapData, $actualConfigBootstrapData);
        $this->assertSame($expectedDirectoryBootstrapData, $actualDirectoryBootstrapData);
        $this->assertSame($expectedIniBootstrapData, $actualIniBootstrapData);
        $this->assertSame($expectedLoggingBootstrapData, $actualLoggingBootstrapData);

    }


}