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

    public function testErrorHandlerBootstrapWithInvalidData() {

        $exceptionThrown = false;
        try {
            $bootstrapData = array();
            $bootstrapData['handler'] = 'SprayFire.NonExistent.Object';
            $bootstrapData['method'] = 'trap';

            $BootstrapConfig = new \SprayFire\Config\ArrayConfig($bootstrapData);
            $ErrorHandlerBootstrap = new \SprayFire\Bootstrap\ErrorHandlerBootstrap($BootstrapConfig);
            $ErrorHandler = $ErrorHandlerBootstrap->runBootstrap();
        } catch (\SprayFire\Exception\TypeNotFoundException $TypeNotFound) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

}