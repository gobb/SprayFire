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

        $LogDelegator = $this->getNewLogDelegator();
        $Config = new \SprayFire\Config\ArrayConfig($configs, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $configObjects = $Bootstrap->runBootstrap();

        $this->assertInternalType('array', $configObjects, 'The return value from ConfigBootstrap is not an array');

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $loggedMessages = $ErrorLogger->getLoggedMessages();

        $SprayFireRollTide = $configObject['SprayFireRollTide'];
        $PrimaryConfig = $configObject['PrimaryConfig'];

        $this->assertInstanceOf("\\SprayFire\\Config\\ArrayConfig", $SprayFireRollTide);
        $this->assertInstanceOf("\\SprayFire\\Config\\JsonConfig", $PrimaryConfig);
        $this->assertSame('best', $SprayFireRollTide->sprayfire);
        $this->assertSame('tide', $SprayFireRollTide->roll);
        $this->assertSame('Roll Tide!', $PrimaryConfig->app->{'deep-one'}->{'deep-two'}->{'deep-three'}->value);
        $this->assertEmpty($loggedMessages);
    }

    public function testInvalidConfigBootstrapWithNonExistentInterface() {
        $LogDelegator = $this->getNewLogDelegator();

        $data = array('interface' => 'SprayFire.Some.NonExistent.Interface');
        $Config = new \SprayFire\Config\ArrayConfig($data, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $configObjects = $Bootstrap->runBootstrap();

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $loggedMessages = $ErrorLogger->getLoggedMessages();
        $expectedLogMessages = array(
            array(
                'message' => 'The type passed, \\SprayFire\\Some\\NonExistent\\Interface, could not be found or loaded.',
                'options' => array()
            )
        );
        $this->assertInternalType('array', $configObjects, 'The return value from ConfigBootstrap is not an array');
        $this->assertSame($expectedLogMessages, $loggedMessages);
    }

    public function testInvalidConfigFilePassed() {
        $invalidConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/Config/json/no-exist.json';
        $configs = array(
            array(
                'object' => 'SprayFire.Config.ArrayConfig',
                'data' => array('sprayfire' => 'best', 'roll' => 'tide'),
                'map-key' => 'SprayFireRollTide'
            ),
            array(
                'object' => 'SprayFire.Config.JsonConfig',
                'data' => $invalidConfigPath,
                'map-key' => 'PrimaryConfig'
            )
        );

        $LogDelegator = $this->getNewLogDelegator();

        $Config = new \SprayFire\Config\ArrayConfig($configs, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $configObjects = $Bootstrap->runBootstrap();

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $loggedMessages = $ErrorLogger->getLoggedMessages();

        $expectedLogMessages = array(
            array(
                'message' => 'Unable to instantiate the Configuration object, PrimaryConfig, or it does not implement Object interface.',
                'options' => array()
            )
        );
        $this->assertArrayHasKey('SprayFireRollTide', $configObjects);
        $this->assertArrayNotHasKey('PrimaryConfig', $configObjects);
        $this->assertSame($expectedLogMessages, $loggedMessages);
    }

    protected function getNewLogDelegator() {
        $everythingElseLog = 'SprayFire.Logging.Logifier.NullLogger';
        $errorLog = 'SprayFire.Test.Helpers.DevelopmentLogger';
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setDebugLogger($everythingElseLog);
        $LogDelegator->setEmergencyLogger($everythingElseLog);
        $LogDelegator->setInfoLogger($everythingElseLog);
        $LogDelegator->setErrorLogger($errorLog);
        return $LogDelegator;
    }

    protected function getErrorLogger($Overseer) {
        $ReflectedDelegator = new \ReflectionObject($Overseer);
        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $ErrorLogger = $ErrorLoggerProperty->getValue($Overseer);
        return $ErrorLogger;
    }

}