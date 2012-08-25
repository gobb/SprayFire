<?php

/**
 * @file
 * @brief Holds a class to test the ErrorHandler for proper error trapping and
 * logging.
 */

namespace SprayFire\Test\Cases\Error;

/**
 * @brief
 */
class HandlerTest extends \PHPUnit_Framework_TestCase {

    public function testErrorHandlerFunctionUnhandledSeverity() {
        $TestLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($TestLogger, $TestLogger, $TestLogger, $TestLogger);
        $ErrorHandler = new \SprayFire\Handler($LogDelegator);
        $trappedError = $ErrorHandler->trapError(E_RECOVERABLE_ERROR, 'This is an error message with unhandled severity.', 'file.php', 14);
        $this->assertFalse($trappedError);

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $actualErrorData = $ErrorLogger->getLoggedMessages();
        $expectedErrorData = array();
        $expectedErrorData[0] = array();
        $expectedErrorData[0]['message'] = 'This is an error message with unhandled severity.;file.php;line:14';
        $expectedErrorData[0]['options'] = array();
        $this->assertSame($expectedErrorData, $actualErrorData);

    }

    public function testErrorHandlerFunctionUnknownSeverity() {
        $TestLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($TestLogger, $TestLogger, $TestLogger, $TestLogger);
        $ErrorHandler = new \SprayFire\Handler($LogDelegator);
        $ErrorHandler->trapError(E_COMPILE_ERROR, 'This is an error message with unknown severity', 'file.php', 100);

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $actualErrorData = $ErrorLogger->getLoggedMessages();
        $expectedErrorData = array();
        $expectedErrorData[0] = array();
        $expectedErrorData[0]['message'] = 'This is an error message with unknown severity;file.php;line:100';
        $expectedErrorData[0]['options'] = array();
        $this->assertSame($expectedErrorData, $actualErrorData);

    }

    public function testErrorHandlerFunctionWithErrorReportingTurnedOff() {
        $TestLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($TestLogger, $TestLogger, $TestLogger, $TestLogger);
        $ErrorHandler = new \SprayFire\Handler($LogDelegator);
        \set_error_handler(array($ErrorHandler, 'trapError'));

        $originalErrorReporting = \error_reporting();
        \error_reporting(0);
        $this->assertFalse($ErrorHandler->trapError(E_USER_DEPRECATED, 'Should return false.'));
        \error_reporting($originalErrorReporting);

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $actualErrorData = $ErrorLogger->getLoggedMessages();
        $expectedErrorData = array();
        $this->assertSame($expectedErrorData, $actualErrorData);
    }

    protected function getErrorLogger(\SprayFire\Logging\LogOverseer $LogDelegator) {
        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $ErrorLogProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLogProperty->setAccessible(true);
        return $ErrorLogProperty->getValue($LogDelegator);
    }

}