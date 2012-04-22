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
        $SprayFireContainer = $Bootstrap->runBootstrap();
        $this->assertInstanceOf('\\SprayFire\\Structure\\Map\\GenericMap', $SprayFireContainer, 'The SprayFireContainer is not a GenericMap');
        $this->assertTrue($SprayFireContainer->containsKey('PathGenerator'), 'The SprayFireContainer does not contain a PathGenerator');
        $PathGenerator = $SprayFireContainer->getObject('PathGenerator');
        $this->assertInstanceOf('\\SprayFire\\Util\\Directory', $PathGenerator);
        $this->assertTrue($SprayFireContainer->containsKey('LogOverseer'), 'The SprayFireContainer does not contain a LogOverseer');
        $LogOverseer = $SprayFireContainer->getObject('LogOverseer');
        $this->assertInstanceOf('\\SprayFire\\Logging\\Logifier\\LogDelegator', $LogOverseer);
    }



}

// End PrimaryBootstrapTest
