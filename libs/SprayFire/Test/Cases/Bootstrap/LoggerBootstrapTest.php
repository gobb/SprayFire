<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Bootstrap;

/**
 * @brief
 */
class LoggerBootstrapTest extends \PHPUnit_Framework_TestCase {

    public function testLogOverseerCreationWithValidConfig() {

        $infoLogFile = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/info.txt';
        \touch($infoLogFile);
        \chmod($infoLogFile, 0777);
        $InfoFileObject = new \SplFileInfo($infoLogFile);
        $config = array(
            'emergency' => array(
                'object' => 'SprayFire.Logging.Logifier.SysLogLogger',
                'blueprint' => array()
            ),
            'error' => array(
                'object' => 'SprayFire.Logging.Logifier.ErrorLogLogger',
                'blueprint' => array()
            ),
            'info' => array(
                'object' => 'SprayFire.Logging.Logifier.FileLogger',
                'blueprint' => array($InfoFileObject)
            ),
            'debug' => array(
                'object' => 'SprayFire.Test.Helpers.DevelopmentLogger',
                'blueprint' => array()
            )
        );
        $ConfigObject = new \SprayFire\Config\ArrayConfig($config);
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LoggerBootstrap = new \SprayFire\Bootstrap\LoggerBootstrap($ConfigObject, $LoggerFactory);
        $LogDelegator = $LoggerBootstrap->runBootstrap();

        $this->assertTrue($LogDelegator instanceof \SprayFire\Logging\Logifier\LogDelegator);

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $EmergencyLoggerProperty = $ReflectedDelegator->getProperty('EmergencyLogger');
        $EmergencyLoggerProperty->setAccessible(true);
        $this->assertTrue($EmergencyLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\SysLogLogger);

        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $this->assertTrue($ErrorLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\ErrorLogLogger);

        $DebugLoggerProperty = $ReflectedDelegator->getProperty('DebugLogger');
        $DebugLoggerProperty->setAccessible(true);
        $this->assertTrue($DebugLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Test\Helpers\DevelopmentLogger);

        $InfoLoggerProperty = $ReflectedDelegator->getProperty('InfoLogger');
        $InfoLoggerProperty->setAccessible(true);
        $this->assertTrue($InfoLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\FileLogger);

    }

    public function testLogOverSeerCreationWithMissingErrorAndDebugValues() {
        $infoLogFile = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/info.txt';
        \touch($infoLogFile);
        \chmod($infoLogFile, 0777);
        $InfoFileObject = new \SplFileInfo($infoLogFile);
        $config = array(
            'emergency' => array(
                'object' => 'SprayFire.Logging.Logifier.SysLogLogger',
                'blueprint' => ''
            ),
            'info' => array(
                'object' => 'SprayFire.Logging.Logifier.FileLogger',
                'blueprint' => array($InfoFileObject)
            )
        );
        $ConfigObject = new \SprayFire\Config\ArrayConfig($config, false);
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LoggerBootstrap = new \SprayFire\Bootstrap\LoggerBootstrap($ConfigObject, $LoggerFactory);
        $LogDelegator = $LoggerBootstrap->runBootstrap();

        $this->assertTrue($LogDelegator instanceof \SprayFire\Logging\Logifier\LogDelegator);

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $EmergencyLoggerProperty = $ReflectedDelegator->getProperty('EmergencyLogger');
        $EmergencyLoggerProperty->setAccessible(true);
        $this->assertTrue($EmergencyLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\SysLogLogger);

        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $this->assertTrue($ErrorLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\NullLogger);

        $DebugLoggerProperty = $ReflectedDelegator->getProperty('DebugLogger');
        $DebugLoggerProperty->setAccessible(true);
        $this->assertTrue($DebugLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\NullLogger);

        $InfoLoggerProperty = $ReflectedDelegator->getProperty('InfoLogger');
        $InfoLoggerProperty->setAccessible(true);
        $this->assertTrue($InfoLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\FileLogger);
    }

    public function testLogOverseerCreationWithMissingEmergAndInfoConfig() {
        $infoLogFile = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/info.txt';
        \touch($infoLogFile);
        \chmod($infoLogFile, 0777);
        $config = array(
            'error' => array(
                'object' => 'SprayFire.Logging.Logifier.ErrorLogLogger',
                'blueprint' => array()
            ),
            'debug' => array(
                'object' => 'SprayFire.Test.Helpers.DevelopmentLogger',
                'blueprint' => array()
            )
        );
        $ConfigObject = new \SprayFire\Config\ArrayConfig($config, false);
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LoggerBootstrap = new \SprayFire\Bootstrap\LoggerBootstrap($ConfigObject, $LoggerFactory);
        $LogDelegator = $LoggerBootstrap->runBootstrap();

        $this->assertTrue($LogDelegator instanceof \SprayFire\Logging\Logifier\LogDelegator);

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $EmergencyLoggerProperty = $ReflectedDelegator->getProperty('EmergencyLogger');
        $EmergencyLoggerProperty->setAccessible(true);
        $this->assertTrue($EmergencyLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\NullLogger);

        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $this->assertTrue($ErrorLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\ErrorLogLogger);

        $DebugLoggerProperty = $ReflectedDelegator->getProperty('DebugLogger');
        $DebugLoggerProperty->setAccessible(true);
        $this->assertTrue($DebugLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Test\Helpers\DevelopmentLogger);

        $InfoLoggerProperty = $ReflectedDelegator->getProperty('InfoLogger');
        $InfoLoggerProperty->setAccessible(true);
        $this->assertTrue($InfoLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\NullLogger);
    }

    public function tearDown() {
        $infoLogFile = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/info.txt';
        if (\file_exists($infoLogFile)) {
            \unlink($infoLogFile);
        }
    }
}