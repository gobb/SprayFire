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

    public function testCallbackInvokingAnonymousFunction() {

        $testData = array();
        $eventName = \SprayFire\Mediator\DispatcherEvents::BEFORE_CONTROLLER_INVOKED;

        $function = function(\SprayFire\Mediator\Event $Event) use(&$testData) {
            $testData['eventName'] = $Event->getEventName();
            $testData['target'] = $Event->getTarget();
            $testData['arguments'] = $Event->getArguments();
        };

        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);

        $target = 'test';
        $arguments = array(1,2,3,4);
        $Event = new \SprayFire\Mediator\FireMediator\Event($eventName, $target, $arguments);

        $Callback->invoke($Event);
        $this->assertSame($target, $testData['target']);
        $this->assertSame($eventName, $testData['eventName']);
        $this->assertSame($arguments, $testData['arguments']);

    }

    public function tearDown() {

    }

}
