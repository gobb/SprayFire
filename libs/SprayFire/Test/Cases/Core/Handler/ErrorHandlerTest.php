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

namespace SprayFire\Test\Cases\Core\Handler;

/**
 * @brief
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase {

    public function testErrorHandlerFunctionNotInDevelopmentMode() {
        $Log = new \SprayFire\Logging\NullLogger();
        $ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($Log);
        \set_error_handler(array($ErrorHandler, 'trap'));

        \trigger_error('The first error message', E_USER_WARNING);
        $timestamp = \date('M-d-Y H:i:s');

        $expectedErrorData = array();
        $expectedErrorData[0]['severity'] = 'E_USER_WARNING';
        $expectedErrorData[0]['message'] = 'The first error message';
        $expectedErrorData[0]['file'] = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/Cases/Core/Handler/ErrorHandlerTest.php';
        $expectedErrorData[0]['line'] = 36;

        $this->assertSame($expectedErrorData, $ErrorHandler->getTrappedErrors());
    }

    public function testErrorHandlerFunctionInDevelopmentMode() {
        $Log = new \SprayFire\Logging\NullLogger();
        $ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($Log, true);
        \set_error_handler(array($ErrorHandler, 'trap'));

        \trigger_error('Another error message', E_USER_NOTICE);
        $timestamp = \date('M-d-Y H:i:s');

        $expectedErrorData = array();
        $expectedErrorData[0]['severity'] = 'E_USER_NOTICE';
        $expectedErrorData[0]['message'] = 'Another error message';
        $expectedErrorData[0]['file'] = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/Cases/Core/Handler/ErrorHandlerTest.php';
        $expectedErrorData[0]['line'] = 53;
        $expectedErrorData[0]['context'] = array('Log' => $Log, 'ErrorHandler' => $ErrorHandler);

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
        $Log = new \SprayFire\Logging\NullLogger();
        $ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($Log);
        \set_error_handler(array($ErrorHandler, 'trap'));

        $this->assertFalse($ErrorHandler->trap(E_RECOVERABLE_ERROR, 'This is an error message', 'file.php', 100));

        $expectedErrorData = array();
        $expectedErrorData[0]['severity'] = 'E_RECOVERABLE_ERROR';
        $expectedErrorData[0]['message'] = 'This is an error message';
        $expectedErrorData[0]['file'] = 'file.php';
        $expectedErrorData[0]['line'] = 100;
        $this->assertSame($expectedErrorData, $ErrorHandler->getTrappedErrors());
    }

    public function testErrorHandlerFunctionUnknownSeverity() {
        $Log = new \SprayFire\Logging\NullLogger();
        $ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($Log);
        \set_error_handler(array($ErrorHandler, 'trap'));

        $ErrorHandler->trap(E_COMPILE_ERROR, 'This is an error message', 'file.php', 100);

        $expectedErrorData = array();
        $expectedErrorData[0]['severity'] = 'E_UNKNOWN_SEVERITY';
        $expectedErrorData[0]['message'] = 'This is an error message';
        $expectedErrorData[0]['file'] = 'file.php';
        $expectedErrorData[0]['line'] = 100;
        $this->assertSame($expectedErrorData, $ErrorHandler->getTrappedErrors());
    }

    public function testErrorHandlerFunctionWithErrorReportingTurnedOff() {
        $Log = new \SprayFire\Logging\NullLogger();
        $ErrorHandler = new \SprayFire\Core\Handler\ErrorHandler($Log, true);
        \set_error_handler(array($ErrorHandler, 'trap'));

        $originalErrorReporting = \error_reporting();
        \error_reporting(0);
        $this->assertFalse($ErrorHandler->trap(E_USER_DEPRECATED, 'Should return false.'));

        $this->assertSame(array(), $ErrorHandler->getTrappedErrors());
    }

}