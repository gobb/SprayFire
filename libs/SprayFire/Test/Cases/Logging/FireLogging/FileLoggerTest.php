<?php

/**
 * @file
 * @brief Will test the various functionality of SprayFire.Logging.FireLogging.FileLogger
 */

namespace SprayFire\Test\Cases\Logging\FireLogging;

class FileLoggerTest extends \PHPUnit_Framework_TestCase {

    private $readOnlyLog;

    private $writableLog;

    public function setUp() {
        $logPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs';
        $this->readOnlyLog = $logPath . '/readonly-log.txt';
        $this->writableLog = $logPath . 'writable-log.txt';
    }

    public function testBasicFileLogging() {
        $file = $this->writableLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\FireLogging\FileLogger($LogFile);

        $Logger->log('something');
        $firstTimestamp = \date('[M-d-Y H:i:s]');
        $Logger->log('else');
        $secondTimestamp = \date('[M-d-Y H:i:s]');

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array($firstTimestamp . ' := something', $secondTimestamp . ' := else', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame($expected[$i], \trim($line));
            $i++;
        }
    }

    public function testEmptyLogMessage() {
        $file = $this->writableLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\FireLogging\FileLogger($LogFile);
        $blankMessage = '';
        $Logger->log($blankMessage);
        $firstTimestamp = \date('[M-d-Y H:i:s]');
        $Logger->log($blankMessage);
        $secondTimestamp = \date('[M-d-Y H:i:s]');

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array($firstTimestamp . ' := Blank message.', $secondTimestamp . ' := Blank message.', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame($expected[$i], \trim($line));
            $i++;
        }
    }

    public function testLengthOption() {
        $file = $this->writableLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\FireLogging\FileLogger($LogFile);

        $options = array();
        $noTimestampMessage = 'This is a message long enough to be long.';
        $message = \date('[M-d-Y H:i:s]') . ' := ' . $noTimestampMessage;
        $strLength = \strlen($message);
        $modStrLength = $strLength / 2;

        $options['length'] = (int) $modStrLength;
        $Logger->log($noTimestampMessage, $options);

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array(\substr($message, 0, $modStrLength));
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame($expected[$i], \trim($line));
            $i++;
        }

    }

    public function testTimestampOption() {
        $file = $this->writableLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\FireLogging\FileLogger($LogFile);

        $options = array();
        $timestamp = 'M-d-Y';
        $noTimestampMessage = 'This is a message long enough to be long.';
        $message = \date($timestamp) . ' := ' . $noTimestampMessage;
        $options['timestampFormat'] = $timestamp;
        $Logger->log($noTimestampMessage, $options);

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array($message);
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $expectedVal = (isset($expected[$i])) ? $expected[$i] : '';
            $this->assertSame($expectedVal, \trim($line));
            $i++;
        }
    }

    public function tearDown() {
        if (\file_exists($this->readOnlyLog)) {
            \unlink($this->readOnlyLog);
        }
        $this->assertFalse(\file_exists($this->readOnlyLog));

        if (\file_exists($this->writableLog)) {
            \unlink($this->writableLog);
        }
        $this->assertFalse(\file_exists($this->writableLog));
    }

}