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
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase {

    public function testErrorHandlerFunctionNotInDevelopmentMode() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionPool);
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $LogDelegator->setDebugLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $ErrorHandler = new \SprayFire\Error\Handler($LogDelegator);
        $originalHandler = \set_error_handler(array($ErrorHandler, 'trap'));

        \trigger_error('The first error message', E_USER_WARNING);

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $actualErrorData = $ErrorLogger->getLoggedMessages();
        $expectedErrorData = array();
        $expectedErrorData[0]['message'] = 'The first error message;' . __FILE__ . ';line:25';
        $expectedErrorData[0]['options'] = array();
        $this->assertSame($expectedErrorData, $actualErrorData);

        \set_error_handler($originalHandler);
    }

    public function testErrorHandlerFunctionUnhandledSeverity() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionPool);
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $ErrorHandler = new \SprayFire\Error\Handler($LogDelegator);
        $trappedError = $ErrorHandler->trap(E_RECOVERABLE_ERROR, 'This is an error message with unhandled severity.', 'file.php', 14);
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
        $ReflectionPool = new \Artax\ReflectionPool();
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionPool);
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $ErrorHandler = new \SprayFire\Error\Handler($LogDelegator);
        $ErrorHandler->trap(E_COMPILE_ERROR, 'This is an error message with unknown severity', 'file.php', 100);

        $ErrorLogger = $this->getErrorLogger($LogDelegator);
        $actualErrorData = $ErrorLogger->getLoggedMessages();
        $expectedErrorData = array();
        $expectedErrorData[0] = array();
        $expectedErrorData[0]['message'] = 'This is an error message with unknown severity;file.php;line:100';
        $expectedErrorData[0]['options'] = array();
        $this->assertSame($expectedErrorData, $actualErrorData);

    }

    public function testErrorHandlerFunctionWithErrorReportingTurnedOff() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory($ReflectionPool);
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $ErrorHandler = new \SprayFire\Error\Handler($LogDelegator);
        \set_error_handler(array($ErrorHandler, 'trap'));

        $originalErrorReporting = \error_reporting();
        \error_reporting(0);
        $this->assertFalse($ErrorHandler->trap(E_USER_DEPRECATED, 'Should return false.'));
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