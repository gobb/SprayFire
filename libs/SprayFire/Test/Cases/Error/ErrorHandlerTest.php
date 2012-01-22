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
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $LogDelegator->setDebugLogger('SprayFire.Test.Helpers.DevelopmentLogger');
        $ErrorHandler = new \SprayFire\Error\ErrorHandler($LogDelegator);
        \set_error_handler(array($ErrorHandler, 'trap'));

        \trigger_error('The first error message', E_USER_WARNING);
        $this->assertSame(array(), $ErrorHandler->getTrappedErrors());

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $ErrorLogProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLogProperty->setAccessible(true);
        $ErrorLogger = $ErrorLogProperty->getValue($LogDelegator);
        $actualErrorData = $ErrorLogger->getLoggedMessages();
        $expectedErrorData = array();
        $expectedErrorData[0]['message'] = 'The first error message';
        $expectedErrorData[0]['options'] = array();
        $this->assertSame($expectedErrorData, $actualErrorData);

        $DebugLogProperty = $ReflectedDelegator->getProperty('DebugLogger');
        $DebugLogProperty->setAccessible(true);
        $DebugLogger = $DebugLogProperty->getValue($LogDelegator);
        $actualDebugData = $DebugLogger->getLoggedMessages();
        $expectedDebugData = array();
        $this->assertSame($expectedDebugData, $actualDebugData);
    }

    public function testErrorHandlerFunctionInDevelopmentMode() {
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Logging.Logifier.NullLogger');
        $LogDelegator->setDebugLogger('SprayFire.Logging.Logifier.NullLogger');
        $ErrorHandler = new \SprayFire\Error\ErrorHandler($LogDelegator, true);
        \set_error_handler(array($ErrorHandler, 'trap'));

        \trigger_error('Another error message', E_USER_NOTICE);
        $timestamp = \date('M-d-Y H:i:s');

        $expectedErrorData = array();
        $expectedErrorData[0]['severity'] = 'E_USER_NOTICE';
        $expectedErrorData[0]['message'] = 'Another error message';
        $expectedErrorData[0]['file'] = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/Cases/Error/ErrorHandlerTest.php';
        $expectedErrorData[0]['line'] = 53;
        $expectedErrorData[0]['context'] = array('LoggerFactory' => $LoggerFactory, 'LogDelegator' => $LogDelegator, 'ErrorHandler' => $ErrorHandler);

        $this->assertSame($expectedErrorData, $ErrorHandler->getTrappedErrors());

        $expectedMessages = array();
        $expectedMessages[0]['timestamp'] = $timestamp;
        $expectedMessages[0]['info'] = array(
            'severity' => 'E_USER_NOTICE',
            'message' => 'Another error message',
            'file' => \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/Cases/Core/Handler/ErrorHandlerTest.php',
            'line' => 59,
            'context' => array(
                'Log' => $Log,
                'ErrorHandler' => $ErrorHandler
            )
        );
    }

    public function testErrorHandlerFunctionUnhandledSeverity() {
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Logging.Logifier.NullLogger');
        $LogDelegator->setDebugLogger('SprayFire.Logging.Logifier.NullLogger');
        $ErrorHandler = new \SprayFire\Error\ErrorHandler($LogDelegator, true);
        \set_error_handler(array($ErrorHandler, 'trap'));
        $trappedError = $ErrorHandler->trap(E_RECOVERABLE_ERROR, 'This is an error message', 'file.php', 100);
        $this->assertFalse($trappedError);

        $expectedErrorData = array();
        $this->assertSame($expectedErrorData, $ErrorHandler->getTrappedErrors());
    }

    public function testErrorHandlerFunctionUnknownSeverity() {
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Logging.Logifier.NullLogger');
        $LogDelegator->setDebugLogger('SprayFire.Logging.Logifier.NullLogger');
        $ErrorHandler = new \SprayFire\Error\ErrorHandler($LogDelegator, true);
        \set_error_handler(array($ErrorHandler, 'trap'));

        $ErrorHandler->trap(E_COMPILE_ERROR, 'This is an error message', 'file.php', 100);

        $expectedErrorData = array();
        $expectedErrorData[0]['severity'] = 'E_UNKNOWN_SEVERITY';
        $expectedErrorData[0]['message'] = 'This is an error message';
        $expectedErrorData[0]['file'] = 'file.php';
        $expectedErrorData[0]['line'] = 100;
        $expectedErrorData[0]['context'] = null;
        $this->assertSame($expectedErrorData, $ErrorHandler->getTrappedErrors());
    }

    public function testErrorHandlerFunctionWithErrorReportingTurnedOff() {
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
        $LogDelegator->setErrorLogger('SprayFire.Logging.Logifier.NullLogger');
        $LogDelegator->setDebugLogger('SprayFire.Logging.Logifier.NullLogger');
        $ErrorHandler = new \SprayFire\Error\ErrorHandler($LogDelegator, true);
        \set_error_handler(array($ErrorHandler, 'trap'));

        $originalErrorReporting = \error_reporting();
        \error_reporting(0);
        $this->assertFalse($ErrorHandler->trap(E_USER_DEPRECATED, 'Should return false.'));

        $this->assertSame(array(), $ErrorHandler->getTrappedErrors());
        \error_reporting($originalErrorReporting);
    }

}