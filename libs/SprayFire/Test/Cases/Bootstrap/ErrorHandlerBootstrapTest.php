<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Bootstrap;

/**
 * @brief
 */
class ErrorHandlerBootstrapTest extends \PHPUnit_Framework_TestCase {

    public function testErrorHandlerBootstrap() {
        $bootstrapData = array();
        $bootstrapData['handler'] = 'SprayFire.Error.ErrorHandler';
        $bootstrapData['method'] = 'trap';

        $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
        $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($this->getLogOverseer(), $BootstrapConfig);
        $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();

        $errorHandlerSetByBootstrap = \set_error_handler(function() {
            return false;
        });

        $this->assertSame(array('SprayFire\Error\ErrorHandler', 'trap'), $errorHandlerSetByBootstrap);

    }

    public function testErrorHandlerBootstrapWithInvalidHandler() {

        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.NonExistent.Object';
            $bootstrapData['method'] = 'trap';

            $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($this->getLogOverseer(), $BootstrapConfig);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\InvalidArgumentException $InvalArgExc) {
            $this->assertSame('The class, \\SprayFire\\NonExistent\\Object, could not be loaded.', $InvalArgExc->getMessage());
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testErrorHandlerBootstrapWithNoHandlerOrMethodSet() {
        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.NonExistent.Object';
            $bootstrapData['method'] = 'trap';

            $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($this->getLogOverseer(), $BootstrapConfig);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\InvalidArgumentException $InvalArgExc) {
            $this->assertSame('The class, \\SprayFire\\NonExistent\\Object, could not be loaded.', $InvalArgExc->getMessage());
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

            $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($this->getLogOverseer(), $BootstrapConfig);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\InvalidArgumentException $InvalArgExc) {
            $this->assertSame('', $InvalArgExc->getMessage());
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    protected function getLogOverseer() {
        $LoggerFactory = new \SprayFire\Logging\Logifier\LoggerFactory();
        return new \SprayFire\Logging\Logifier\LogDelegator($LoggerFactory);
    }

}