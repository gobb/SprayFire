<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of CallbackTest
 */

namespace SprayFire\Test\Cases;

class CallbackTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {

    }

    public function testCallbackGettingEventName() {
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName);
        $this->assertSame($eventName, $Callback->getEventName());
    }

    public function tearDown() {

    }

}
