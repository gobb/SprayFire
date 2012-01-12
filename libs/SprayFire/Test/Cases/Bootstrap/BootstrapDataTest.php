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

    public function testValidBootstrapData() {
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
        $expectedIniBootstrapData['global'] = array();
        $expectedIniBootstrapData['global']['allow_url_fopen'] = 0;
        $expectedIniBootstrapData['global']['allow_url_include'] = 0;
        $expectedIniBootstrapData['global']['asp_tags'] = 0;
        $expectedIniBootstrapData['global']['date.timezone'] = 'America/New_York';
        $expectedIniBootstrapData['global']['default_charset'] = 'UTF-8';
        $expectedIniBootstrapData['global']['default_mimetype'] = 'text/html';
        $expectedIniBootstrapData['global']['assert.active'] = 0;
        $expectedIniBootstrapData['global']['magic_quotes_gpc'] = 0;
        $expectedIniBootstrapData['global']['magic_quotes_runtime'] = 0;
        $expectedIniBootstrapData['global']['expose_php'] = 0;

        $expectedIniBootstrapData['production'] = array();
        $expectedIniBootstrapData['production']['display_errors'] = 0;
        $expectedIniBootstrapData['production']['display_startup_errors'] = 0;
        $expectedIniBootstrapData['production']['error_reporting'] = \E_ALL & ~\E_NOTICE;

        $expectedIniBootstrapData['development'] = array();
        $expectedIniBootstrapData['development']['display_errors'] = 1;
        $expectedIniBootstrapData['development']['display_startup_errors'] = 1;
        $expectedIniBootstrapData['development']['error_reporting'] = -1;
        $actualIniBootstrapData = $BootstrapData->IniBootstrap;

        $expectedConfigBootstrapData = array();
        $expectedConfigBootstrapData['sprayFireConfig']['file'] = array('json', 'sprayfire-configuration.json');
        $expectedConfigBootstrapData['sprayFireConfig']['object'] = 'SprayFire.Config.JsonConfig';
        $expectedConfigBootstrapData['sprayFireConfig']['map-key'] = 'SprayFireConfig';
        $expectedConfigBootstrapData['routesConfig']['file'] = array('json', 'routes.json');
        $expectedConfigBootstrapData['routesConfig']['object'] = 'SprayFire.Config.JsonConfig';
        $expectedConfigBootstrapData['routesConfig']['map-key'] = 'RoutesConfig';
        $expectedConfigBootstrapData['pluginsConfig']['file'] = array('json', 'plugins.json');
        $expectedConfigBootstrapData['pluginsConfig']['object'] = 'SprayFire.Config.JsonConfig';
        $expectedConfigBootstrapData['pluginsConfig']['map-key'] = 'PluginsConfig';
        $actualConfigBootstrapData = $BootstrapData->ConfigBootstrap;

        $expectedLoggingBootstrapData = array();
        $expectedLoggingBootstrapData['emergency'] = array();
        $expectedLoggingBootstrapData['emergency']['object'] = 'SprayFire.Logging.Logifier.SysLogLogger';
        $expectedLoggingBootstrapData['emergency']['blueprint'] = array('SprayFire', \LOG_NDELAY, \LOG_USER);
        $expectedLoggingBootstrapData['error'] = array();
        $expectedLoggingBootstrapData['error']['object'] = 'SprayFire.Logging.Logifier.ErrorLogLogger';
        $expectedLoggingBootstrapData['error']['blueprint'] = array();
        $expectedLoggingBootstrapData['debug'] = array();
        $expectedLoggingBootstrapData['debug']['object'] = 'SprayFire.Logging.Logifier.DebugLogger';
        $expectedLoggingBootstrapData['debug']['blueprint'] = array('sprayfire-debug.txt');
        $expectedLoggingBootstrapData['info']['object'] = 'SprayFire.Logging.Logifier.FileLogger';
        $expectedLoggingBootstrapData['info']['blueprint'] = array('sprayfire-info.txt');
        $actualLoggingBootstrapData = $BootstrapData->LoggingBootstrap;

        $this->assertSame($expectedDirectoryBootstrapData, $actualDirectoryBootstrapData);
        $this->assertSame($expectedConfigBootstrapData, $actualConfigBootstrapData);
        $this->assertSame($expectedIniBootstrapData, $actualIniBootstrapData);
        $this->assertSame($expectedLoggingBootstrapData, $actualLoggingBootstrapData);
    }

    public function testInvalidBootstrapData() {
        $primaryConfig = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/config/primary-configuration-test-no-exist.php';
        $paths = array();
        $BootstrapData = new \SprayFire\Bootstrap\BootstrapData($primaryConfig, $paths);
        $this->assertSame(array(), $BootstrapData->PathGenBootstrap);
        $this->assertSame(array(), $BootstrapData->ConfigBootstrap);
        $this->assertSame(array(), $BootstrapData->LoggingBootstrap);
        $this->assertSame(array(), $BootstrapData->IniBootstrap);
    }


}