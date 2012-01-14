<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Logger;

/**
 * @brief
 */
class ErrorLogLoggerTest extends \PHPUnit_Framework_TestCase {

    public function testLoggingToErrorLog() {
        $errorLogPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs/temp-log-file.txt';
        \touch($errorLogPath);
        \chmod($errorLogPath, 0777);
        $originalErrorLog = \ini_set('error_log', $errorLogPath);

        $Logger = new \SprayFire\Logging\Logifier\ErrorLogLogger();
        $Logger->log('A test message.');

        $expected = \date('[d-M-Y H:i:s]') . ' A test message.' . PHP_EOL;

        $file = \fopen($errorLogPath, 'r');
        $line = \fgets($file);
        \fclose($file);
        \unlink($errorLogPath);
        \ini_set('error_log', $originalErrorLog);
        $this->assertSame($expected, $line);
        $this->assertSame(\ini_get('error_log'), $originalErrorLog);
    }

}