<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of MediatorTest
 */

namespace SprayFire\Test\Cases\Mediator\FireMediator;

class MediatorTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {

    }

    public function testMediatorStoringSingleValidCallback() {
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $function = function(\SprayFire\Mediator\Event $Event) {

        };
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);

        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();
        $Mediator->addCallback($Callback);

        $callbacks = $Mediator->getCallbacks($eventName);
        $this->assertSame($callbacks[0], $Callback);

    }

    public function tearDown() {

    }

}
