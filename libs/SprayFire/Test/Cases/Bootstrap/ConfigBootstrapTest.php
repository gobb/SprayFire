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

        $everythingElseLog = 'SprayFire.Logging.Logifier.NullLogger';
        $errorLog = 'SprayFire.Test.Helpers.DevelopmentLogger';
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setDebugLogger($everythingElseLog);
        $LogDelegator->setEmergencyLogger($everythingElseLog);
        $LogDelegator->setInfoLogger($everythingElseLog);
        $LogDelegator->setErrorLogger($errorLog);

        $Config = new \SprayFire\Config\ArrayConfig($configs, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $ConfigMap = $Bootstrap->runBootstrap();

        $this->assertTrue($ConfigMap instanceof \SprayFire\Structure\Map\RestrictedMap);

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $ErrorLogger = $ErrorLoggerProperty->getValue($LogDelegator);


        $SprayFireRollTide = $ConfigMap->getObject('SprayFireRollTide');
        $PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');
        $loggedMessages = $ErrorLogger->getLoggedMessages();

        $this->assertTrue($SprayFireRollTide instanceof \SprayFire\Config\ArrayConfig);
        $this->assertTrue($PrimaryConfig instanceof \SprayFire\Config\JsonConfig);
        $this->assertSame('best', $SprayFireRollTide->sprayfire);
        $this->assertSame('tide', $SprayFireRollTide->roll);
        $this->assertSame('Roll Tide!', $PrimaryConfig->app->{'deep-one'}->{'deep-two'}->{'deep-three'}->value);
        $this->assertSame(array(), $loggedMessages);

    }

    public function testInvalidConfigBootstrapWithNonExistentInterface() {

        $everythingElseLog = 'SprayFire.Logging.Logifier.NullLogger';
        $errorLog = 'SprayFire.Test.Helpers.DevelopmentLogger';
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setDebugLogger($everythingElseLog);
        $LogDelegator->setEmergencyLogger($everythingElseLog);
        $LogDelegator->setInfoLogger($everythingElseLog);
        $LogDelegator->setErrorLogger($errorLog);

        $data = array('interface' => 'SprayFire.Some.NonExistent.Interface');
        $Config = new \SprayFire\Config\ArrayConfig($data, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $GenericMap = $Bootstrap->runBootstrap();

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $ErrorLogger = $ErrorLoggerProperty->getValue($LogDelegator);

        $loggedMessages = $ErrorLogger->getLoggedMessages();
        $expectedLogMessages = array(
            array(
                'message' => 'The type passed, \\SprayFire\\Some\\NonExistent\\Interface, could not be found or loaded.',
                'options' => array()
            )
        );
        $this->assertTrue($GenericMap instanceof \SprayFire\Structure\Map\GenericMap);
        $this->assertSame($expectedLogMessages, $loggedMessages);

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

        $everythingElseLog = 'SprayFire.Logging.Logifier.NullLogger';
        $errorLog = 'SprayFire.Test.Helpers.DevelopmentLogger';
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setDebugLogger($everythingElseLog);
        $LogDelegator->setEmergencyLogger($everythingElseLog);
        $LogDelegator->setInfoLogger($everythingElseLog);
        $LogDelegator->setErrorLogger($errorLog);

        $Config = new \SprayFire\Config\ArrayConfig($configs, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $ConfigMap = $Bootstrap->runBootstrap();

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $ErrorLogger = $ErrorLoggerProperty->getValue($LogDelegator);

        $loggedMessages = $ErrorLogger->getLoggedMessages();

        $expectedLogMessages = array(
            array(
                'message' => 'Unable to instantiate the Configuration object, PrimaryConfig, or it does not implement Object interface.',
                'options' => array()
            )
        );
        $this->assertTrue($ConfigMap->containsKey('SprayFireRollTide'));
        $this->assertFalse($ConfigMap->containsKey('PrimaryConfig'));
        $this->assertSame($expectedLogMessages, $loggedMessages);
    }

    public function testNoInterfaceSetInConfig() {
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

        $everythingElseLog = 'SprayFire.Logging.Logifier.NullLogger';
        $errorLog = 'SprayFire.Test.Helpers.DevelopmentLogger';
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setDebugLogger($everythingElseLog);
        $LogDelegator->setEmergencyLogger($everythingElseLog);
        $LogDelegator->setInfoLogger($everythingElseLog);
        $LogDelegator->setErrorLogger($errorLog);

        $Log = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $Config = new \SprayFire\Config\ArrayConfig($configs, false);
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($LogDelegator, $Config);
        $ConfigMap = $Bootstrap->runBootstrap();

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $ErrorLogger = $ErrorLoggerProperty->getValue($LogDelegator);

        $loggedMessages = $ErrorLogger->getLoggedMessages();

        $expectedLogMessages = array(
            array(
                'message' => 'The interface is not set properly in the configuration object.',
                'options' => array()
            )
        );
        $this->assertSame($expectedLogMessages, $loggedMessages);
    }

}