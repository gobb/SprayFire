<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of MediatorTest
 */

namespace SprayFire\Test\Cases\Mediator\FireMediator;

class MediatorTest extends \PHPUnit_Framework_TestCase {

    public function testMediatorStoringSingleValidCallback() {
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $function = function(\SprayFire\Mediator\Event $Event) {};
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();
        $Mediator->addCallback($Callback);
        $callbacks = $Mediator->getCallbacks($eventName);
        $this->assertSame($callbacks[0], $Callback);
    }

    public function testMediatorStoringDoubleValidCallback() {
        $firstEventName = \SprayFire\Mediator\DispatcherEvents::BEFORE_CONTROLLER_INVOKED;
        $secondEventName = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $function = function(\SprayFire\Mediator\Event $Event) {};
        $FirstCallback = new \SprayFire\Mediator\FireMediator\Callback($firstEventName, $function);
        $SecondCallback = new \SprayFire\Mediator\FireMediator\Callback($secondEventName, $function);

        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();
        $Mediator->addCallback($FirstCallback);
        $Mediator->addCallback($SecondCallback);

        $firstCallbacks = $Mediator->getCallbacks($firstEventName);
        $secondCallbacks = $Mediator->getCallbacks($secondEventName);

        $this->assertSame($firstCallbacks[0], $FirstCallback);
        $this->assertSame($secondCallbacks[0], $SecondCallback);
    }

    public function testMediatorStoringInvalidCallback() {
        $eventName = 'nonexistent.event_name';
        $function = function() {};
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();

        $this->setExpectedException('\\InvalidArgumentException');
        $Mediator->addCallback($Callback);
    }

    public function testMediatorGettingInvalidCallback() {
        $eventName = 'nonexistent.event_name';
        $function = function() {};
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();
        $actual = $Mediator->getCallbacks($eventName);
        $this->assertSame(array(), $actual);
    }

    public function testMediatorRemovingValidCallback() {
        $eventName = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $function = function() {};
        $Callback = new \SprayFire\Mediator\FireMediator\Callback($eventName, $function);
        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();
        $Mediator->addCallback($Callback);
        $this->assertCount(1, $Mediator->getCallbacks($eventName), 'An event does not have a stored callback though it should');
        $this->assertTrue($Mediator->removeCallback($Callback), 'An event has not been properly removed');
        $this->assertCount(0, $Mediator->getCallbacks($eventName), 'An event has a stored callback though it should not');
    }

    public function testMediatorTriggeringEvents() {
        $eventData = array();
        $firstEvent = \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $secondEvent = \SprayFire\Mediator\DispatcherEvents::BEFORE_CONTROLLER_INVOKED;
        $function = function(\SprayFire\Mediator\Event $Event) use(&$eventData) {
            $eventName = $Event->getEventName();
            $eventData[$eventName] = 'value set';
        };
        $FirstCallback = new \SprayFire\Mediator\FireMediator\Callback($firstEvent, $function);
        $SecondCallback = new \SprayFire\Mediator\FireMediator\Callback($secondEvent, $function);

        $Mediator = new \SprayFire\Mediator\FireMediator\Mediator();
        $Mediator->addCallback($FirstCallback);
        $Mediator->addCallback($SecondCallback);
        $Mediator->triggerEvent($firstEvent, '');
        $Mediator->triggerEvent($secondEvent, '');
        $expected = array(
            $firstEvent => 'value set',
            $secondEvent => 'value set'
        );
        $this->assertSame($expected, $eventData);
    }

    

}
