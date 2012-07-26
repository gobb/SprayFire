<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Logger;

/**
 * @brief
 */
class LoggerFactoryTest extends \PHPUnit_Framework_TestCase {

    protected $JavaConverter;

    public function setUp() {
        $this->JavaConverter = new \SprayFire\JavaNamespaceConverter();
    }

    public function testGettingValidFileLogger() {
        $logPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/temp-test.txt';
        \touch($logPath);
        \chmod($logPath, 0777);
        $Param = new \SplFileInfo($logPath);
        $options = array($Param);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $Factory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionCache, $LogDelegator, $this->JavaConverter);
        $Object = $Factory->makeObject('SprayFire.Logging.Logifier.FileLogger', $options);
        $this->assertTrue($Object instanceof \SprayFire\Logging\Logifier\FileLogger);
        if (\file_exists($logPath)) {
            \unlink($logPath);
        }
    }

    public function testGettingInvalidLogger() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Factory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionCache, $LogDelegator, $this->JavaConverter);
        $Object = $Factory->makeObject('SprayFire.Logging.InvalidLogger');
        $this->assertTrue($Object instanceof \SprayFire\Logging\Logifier\NullLogger);
        $expected = array(
            array(
                'message' => 'There was an error creating the requested object, \\SprayFire\\Logging\\InvalidLogger.  It likely does not exist.',
                'options' => array()
            )

        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

}