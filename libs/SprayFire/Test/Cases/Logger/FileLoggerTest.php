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

namespace SprayFire\Test\Cases\Logger;

/**
 * @brief
 */
class FileLoggerTest extends \PHPUnit_Framework_TestCase {

    private $readOnlyLog;

    private $writableLog;

    private $noTimestampLog;

    public function setUp() {


        $logPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs';

        $this->readOnlyLog = $logPath . '/readonly-log.txt';
        $this->writableLog = $logPath . 'writable-log.txt';
        $this->noTimestampLog = $logPath . 'no-timestamp-log.txt';
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReadOnlyFileObjectFailure() {
        $file = $this->readOnlyLog;
        \touch($file);
        \chmod($file, 0444);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\Logifier\FileLogger($LogFile);
    }

    public function testBasicFileLogging() {
        $file = $this->writableLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\Logifier\FileLogger($LogFile);

        $Logger->log('something');
        $firstTimestamp = \date('M-d-Y H:i:s');
        $Logger->log('else');
        $secondTimestamp = \date('M-d-Y H:i:s');

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array($firstTimestamp . ' := something', $secondTimestamp . ' := else', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame(\trim($line), $expected[$i]);
            $i++;
        }
    }

    public function testEmptyLogMessage() {
        $file = $this->noTimestampLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logging\Logifier\FileLogger($LogFile);
        $blankMessage = '';
        $Logger->log($blankMessage);
        $firstTimestamp = \date('M-d-Y H:i:s');
        $Logger->log($blankMessage);
        $secondTimestamp = \date('M-d-Y H:i:s');

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array($firstTimestamp . ' := Blank message.', $secondTimestamp . ' := Blank message.', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame(\trim($line), $expected[$i]);
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

        if (\file_exists($this->noTimestampLog)) {
            \unlink($this->noTimestampLog);
        }
        $this->assertFalse(\file_exists($this->noTimestampLog));
    }

}

// End FileLoggerTest

// End libs.sprayfire