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
                'object' => 'SprayFire.Logging.Logifier.NullLogger',
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
        $this->assertTrue($EmergencyLoggerProperty->getValue($LogDelegator) instanceof \SprayFire\Logging\Logifier\SysLogLogger);

    }


}