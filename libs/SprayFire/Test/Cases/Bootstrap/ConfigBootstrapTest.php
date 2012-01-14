<?php

/**
 * @file
 * @brief
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Bootstrap;

/**
 * @brief
 */
class ConfigBootstrapTest extends \PHPUnit_Framework_TestCase {

    public function testValidConfigBootstrap() {
        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/Config/json/test-config.json';
        $configs = array(
            'interface' => 'SprayFire.Config.Configuration',
            array(
                'object' => 'SprayFire.Config.ArrayConfig',
                'data' => array('sprayfire' => 'best', 'roll' => 'tide'),
                'map-key' => 'SprayFireRollTide'
            ),
            array(
                'object' => 'SprayFire.Config.JsonConfig',
                'data' => $configPath,
                'map-key' => 'PrimaryConfig'
            )
        );

        $Log = new \SprayFire\Logging\Logifier\NullLogger();
        $Config = new \SprayFire\Config\ArrayConfig($configs, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($Log, $Config);
        $ConfigMap = $Bootstrap->runBootstrap();

        $this->assertTrue($ConfigMap instanceof \SprayFire\Structure\Map\RestrictedMap);

        $SprayFireRollTide = $ConfigMap->getObject('SprayFireRollTide');
        $PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');

        $this->assertTrue($SprayFireRollTide instanceof \SprayFire\Config\ArrayConfig);
        $this->assertTrue($PrimaryConfig instanceof \SprayFire\Config\JsonConfig);
        $this->assertSame('best', $SprayFireRollTide->sprayfire);
        $this->assertSame('tide', $SprayFireRollTide->roll);
        $this->assertSame('Roll Tide!', $PrimaryConfig->app->{'deep-one'}->{'deep-two'}->{'deep-three'}->value);

    }

    public function testInvalidConfigBootstrapWithNonExistentInterface() {
        $exceptionThrown = false;
        $Log = new \SprayFire\Logging\Logifier\NullLogger();
        $timestamp = '';
        try {
            $data = array('interface' => 'SprayFire.Some.NonExistent.Interface');
            $Config = new \SprayFire\Config\ArrayConfig($data);
            $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($Log, $Config);
            $Bootstrap->runBootstrap();
        } catch (\SprayFire\Exception\BootstrapFailedException $FatalRunExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);

    }

    public function testInvalidConfigFilePassed() {
        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/Config/json/no-exist.json';

        $configs = array(
            'interface' => 'SprayFire.Config.Configuration',
            array(
                'object' => 'SprayFire.Config.ArrayConfig',
                'data' => array('sprayfire' => 'best', 'roll' => 'tide'),
                'map-key' => 'SprayFireRollTide'
            ),
            array(
                'object' => 'SprayFire.Config.JsonConfig',
                'data' => $configPath,
                'map-key' => 'PrimaryConfig'
            )
        );

        $Log = new \SprayFire\Logging\Logifier\NullLogger();
        $Config = new \SprayFire\Config\ArrayConfig($configs);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($Log, $Config);
        $ConfigMap = $Bootstrap->runBootstrap();
        $this->assertTrue($ConfigMap->containsKey('SprayFireRollTide'));
        $this->assertFalse($ConfigMap->containsKey('PrimaryConfig'));
    }

}