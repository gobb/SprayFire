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

    public function testGettingValidFileLogger() {
        $logPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/temp-test.txt';
        \touch($logPath);
        \chmod($logPath, 0777);
        $Param = new \SplFileInfo($logPath);
        $options = array($Param);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Factory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionCache);
        $Object = $Factory->makeObject('SprayFire.Logging.Logifier.FileLogger', $options);
        $this->assertTrue($Object instanceof \SprayFire\Logging\Logifier\FileLogger);
        if (\file_exists($logPath)) {
            \unlink($logPath);
        }
    }

    public function testGettingInvalidLogger() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Factory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionCache);
        $Object = $Factory->makeObject('SprayFire.Logging.InvalidLogger');
        $this->assertTrue($Object instanceof \SprayFire\Logging\Logifier\NullLogger);
    }



}