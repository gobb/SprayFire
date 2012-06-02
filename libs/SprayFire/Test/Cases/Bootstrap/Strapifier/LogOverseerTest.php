<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Bootstrap\Strapifier;

/**
 * @brief
 */
class LogOverseerTest extends \PHPUnit_Framework_TestCase {

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
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LoggerBootstrap = new \SprayFire\Bootstrap\Strapifier\LogOverseer($LoggerFactory, $config);
        $LogDelegator = $LoggerBootstrap->runBootstrap();

        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\LogDelegator", $LogDelegator);
        $loggers = $this->getLoggers($LogDelegator);

        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\SysLogLogger", $loggers->emergency);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\ErrorLogLogger", $loggers->error);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\FileLogger", $loggers->info);
        $this->assertInstanceOf("\\SprayFire\\Test\\Helpers\\DevelopmentLogger", $loggers->debug);
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
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LoggerBootstrap = new \SprayFire\Bootstrap\Strapifier\LogOverseer($LoggerFactory, $config);
        $LogDelegator = $LoggerBootstrap->runBootstrap();

        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\LogDelegator", $LogDelegator);
        $loggers = $this->getLoggers($LogDelegator);

        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\SysLogLogger", $loggers->emergency);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\NullLogger", $loggers->error);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\FileLogger", $loggers->info);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\NullLogger", $loggers->debug);
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
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LoggerBootstrap = new \SprayFire\Bootstrap\Strapifier\LogOverseer($LoggerFactory, $config);
        $LogDelegator = $LoggerBootstrap->runBootstrap();

        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\LogDelegator", $LogDelegator);
        $loggers = $this->getLoggers($LogDelegator);

        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\ErrorLogLogger", $loggers->error);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\NullLogger", $loggers->emergency);
        $this->assertInstanceOf("\\SprayFire\\Test\\Helpers\\DevelopmentLogger", $loggers->debug);
        $this->assertInstanceOf("\\SprayFire\\Logging\\Logifier\\NullLogger", $loggers->info);
    }

    public function tearDown() {
        $infoLogFile = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/info.txt';
        if (\file_exists($infoLogFile)) {
            \unlink($infoLogFile);
        }
    }

    protected function getLoggers($LogDelegator) {
        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $EmergencyLoggerProperty = $ReflectedDelegator->getProperty('EmergencyLogger');
        $EmergencyLoggerProperty->setAccessible(true);
        $EmergencyLogger = $EmergencyLoggerProperty->getValue($LogDelegator);

        $ErrorLoggerProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLoggerProperty->setAccessible(true);
        $ErrorLogger = $ErrorLoggerProperty->getValue($LogDelegator);

        $DebugLoggerProperty = $ReflectedDelegator->getProperty('DebugLogger');
        $DebugLoggerProperty->setAccessible(true);
        $DebugLogger = $DebugLoggerProperty->getValue($LogDelegator);

        $InfoLoggerProperty = $ReflectedDelegator->getProperty('InfoLogger');
        $InfoLoggerProperty->setAccessible(true);
        $InfoLogger = $InfoLoggerProperty->getValue($LogDelegator);

        $data = new \stdClass();
        $data->emergency = $EmergencyLogger;
        $data->error = $ErrorLogger;
        $data->debug = $DebugLogger;
        $data->info = $InfoLogger;
        return $data;
    }
}