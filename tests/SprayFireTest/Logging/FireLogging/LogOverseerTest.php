<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFireTest\Logging\FireLogging;

use \SprayFire\Logging as SFLogging,
    \SprayFire\Logging\FireLogging as FireLogging,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Logging.FireLogging
 */
class LogOverseerTest extends PHPUnitTestCase {

    public function testLogOverseerUsingAppropriateEmergencyLogger() {
        $Emergency = $this->getMock('\SprayFire\Logging\Logger');
        $Emergency->expects($this->once())
                  ->method('log')
                  ->with('Log message');

        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse->expects($this->never())
                       ->method('log');

        $LogOverseer = new FireLogging\LogOverseer($Emergency, $EverythingElse, $EverythingElse, $EverythingElse);
        $LogOverseer->logEmergency('Log message');
    }

    public function testLogOverseerUsingAppropriateErrorLogger() {
        $Error = $this->getMock('\SprayFire\Logging\Logger');
        $Error->expects($this->once())
              ->method('log')
              ->with('Error logged message');

        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse->expects($this->never())
                       ->method('log');

        $LogOverseer = new FireLogging\LogOverseer($EverythingElse, $Error, $EverythingElse, $EverythingElse);
        $LogOverseer->logError('Error logged message');
    }

    public function testLogOverseerUsingAppropriateDebugLogger() {
        $Debug = $this->getMock('\SprayFire\Logging\Logger');
        $Debug->expects($this->once())
              ->method('log')
              ->with('Debug message for debugging');

        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse->expects($this->never())
                       ->method('log');

        $LogOverseer = new FireLogging\LogOverseer($EverythingElse, $EverythingElse, $Debug, $EverythingElse);
        $LogOverseer->logDebug('Debug message for debugging');
    }

    public function testLogOverseerUsingAppropriateInfoLogger() {
        $Info = $this->getMock('\SprayFire\Logging\Logger');
        $Info->expects($this->once())
             ->method('log')
             ->with('Info logging message');

        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse->expects($this->never())
                       ->method('log');

        $LogOverseer = new FireLogging\LogOverseer($EverythingElse, $EverythingElse, $EverythingElse, $Info);
        $LogOverseer->logInfo('Info logging message');
    }

    public function testGettingEmergencyLogger() {
        $Emergency = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');

        $LogOverseer = new FireLogging\LogOverseer($Emergency, $EverythingElse, $EverythingElse, $EverythingElse);
        $this->assertSame($Emergency, $LogOverseer->getLogger($LogOverseer::EMERGENCY_LOGGER));
    }

    public function testGettingErrorLogger() {
        $Error = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');

        $LogOverseer = new FireLogging\LogOverseer($EverythingElse, $Error, $EverythingElse, $EverythingElse);
        $this->assertSame($Error, $LogOverseer->getLogger($LogOverseer::ERROR_LOGGER));
    }

    public function testGettingDebugLogger() {
        $Debug = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');

        $LogOverseer = new FireLogging\LogOverseer($EverythingElse, $EverythingElse, $Debug, $EverythingElse);
        $this->assertSame($Debug, $LogOverseer->getLogger($LogOverseer::DEBUG_LOGGER));
    }

    public function testGettingInfoLogger() {
        $Info = $this->getMock('\SprayFire\Logging\Logger');
        $EverythingElse = $this->getMock('\SprayFire\Logging\Logger');

        $LogOverseer = new FireLogging\LogOverseer($EverythingElse, $EverythingElse, $EverythingElse, $Info);
        $this->assertSame($Info, $LogOverseer->getLogger($LogOverseer::INFO_LOGGER));
    }

    public function testGettingNullOnInvalidLoggerKey() {
        $Everything = $this->getMock('\SprayFire\Logging\Logger');
        $LogOverseer = new FireLogging\LogOverseer($Everything, $Everything, $Everything, $Everything);

        $this->assertNull($LogOverseer->getLogger('invalid'));
    }

}
