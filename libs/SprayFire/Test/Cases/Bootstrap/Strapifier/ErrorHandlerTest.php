<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Bootstrap\Strapifier;

/**
 * @brief
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        \set_error_handler(function() {return false;});
    }

    public function testErrorHandlerBootstrap() {
        $bootstrapData = array();
        $bootstrapData['handler'] = 'SprayFire.Error.ErrorHandler';
        $bootstrapData['method'] = 'trap';

        $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\Strapifier\ErrorHandler($this->getLogOverseer(), $bootstrapData);
        $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();

        $errorHandlerSetByBootstrap = \set_error_handler(function() {
            return false;
        });

        $this->assertSame(array($ErrorHandler, 'trap'), $errorHandlerSetByBootstrap);
        $this->assertTrue($ErrorHandler instanceof \SprayFire\Error\ErrorHandler);
    }

    public function testErrorHandlerBootstrapWithInvalidHandler() {

        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.NonExistent.Object';
            $bootstrapData['method'] = 'trap';

            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\Strapifier\ErrorHandler($this->getLogOverseer(), $bootstrapData);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\SprayFire\Exception\BootstrapFailedException $BootstrapExc) {
            $this->assertSame('The class, \\SprayFire\\NonExistent\\Object, could not be loaded.', $BootstrapExc->getMessage());
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);

    }

    public function testErrorHandlerBootstrapWithNoHandlerOrMethodSet() {
        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = '';
            $bootstrapData['method'] = '';

            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\Strapifier\ErrorHandler($this->getLogOverseer(), $bootstrapData);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\SprayFire\Exception\BootstrapFailedException $BootstrapExc) {
            $this->assertSame('The handler or method was not properly set in the configuration.', $BootstrapExc->getMessage());
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testErrorHandlerBootstrapWithInvalidMethod() {

        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.Error.ErrorHandler';
            $bootstrapData['method'] = 'noExistentMethod';

            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\Strapifier\ErrorHandler($this->getLogOverseer(), $bootstrapData);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\SprayFire\Exception\BootstrapFailedException $BootstrapExc) {
            $this->assertSame('The method, noExistentMethod, does not exist in, \\SprayFire\\Error\\ErrorHandler.', $BootstrapExc->getMessage());
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function tearDown() {
        \set_error_handler(function() {return false;});
    }

    protected function getLogOverseer() {
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        return new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
    }

}