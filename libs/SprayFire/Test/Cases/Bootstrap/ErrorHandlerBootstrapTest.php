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
        $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($BootstrapConfig);
        $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();

        $this->assertTrue($ErrorHandler instanceof \SprayFire\Error\ErrorHandler);
    }

    public function testErrorHandlerBootstrapWithInvalidHandler() {

        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.NonExistent.Object';
            $bootstrapData['method'] = 'trap';

            $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($BootstrapConfig);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testErrorHandlerBootstrapWithInvalidMethod() {

        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.Handler.ErrorHandler';
            $bootstrapData['method'] = 'noExistentMethod';

            $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($BootstrapConfig);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

}