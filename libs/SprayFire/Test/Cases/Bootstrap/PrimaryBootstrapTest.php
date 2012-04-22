<?php

namespace SprayFire\Test\Cases\Bootstrap;

/**
 *
 */
class PrimaryBootstrapTest extends \PHPUnit_Framework_TestCase {

    public function testRunningBootstrap() {
        $configFile = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/config/primary-configuration-test.php';
        $this->assertFileExists($configFile);
        $installPath = \SPRAYFIRE_ROOT;
        $libsPath = $installPath . '/libs';
        $configPath = $installPath . '/config';
        $appPath = $installPath . '/app';
        $logsPath = $installPath . '/logs';
        $webPath = $installPath . '/web';
        $paths = \compact('installPath', 'libsPath', 'configPath', 'appPath', 'logsPath', 'webPath');
        $Data = new \SprayFire\Bootstrap\BootstrapData($configFile, $paths);
        $Bootstrap = new \SprayFire\Bootstrap\PrimaryBootstrap($Data);
        $Bootstrap->runBootstrap();
    }



}

// End PrimaryBootstrapTest
