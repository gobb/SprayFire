<?php

/**
 * A test case to ensure the coverage of SprayFire.Mediator.FireMediator.Mediator
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Mediator\FireMediator;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\Mediator\FireMediator as FireMediator;

class MediatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * A list of methods from SprayFire.Mediator.Callback that should be implemented
     * by the mock objects.
     *
     * @property array
     */
    protected $mockCallbackMethods = array(
        'getEventName',
        'invoke',
        'equals',
        'hashCode',
        '__toString'
    );

    protected $Storage;

    /**
     * @property \SprayFire\Mediator\FireMediator\EventRegistry
     */
    protected $Registry;

    protected $Mediator;

    public function setUp() {
        $this->Storage = new FireMediator\CallbackStorage();
        $this->Registry = new FireMediator\EventRegistry($this->Storage);
        $this->Mediator = new FireMediator\Mediator($this->Registry, $this->Storage);
    }

    /**
     * Ensures that a single callback can be added to an event and the appropriate
     * callback is returned as the only object in the events collection.
     */
    public function testMediatorStoringSingleCallbackToSingleEvent() {
        $eventName = 'foo';
        $MockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $MockCallback->expects($this->exactly(2))
                     ->method('getEventName')
                     ->will($this->returnValue($eventName));

        $this->Registry->registerEvent($eventName, 'bar');
        $this->Mediator->addCallback($MockCallback);
        $callbacks = $this->Mediator->getCallbacks($eventName);

        $this->assertCount(1, $callbacks, 'Mediator that should have 1 callback has more or less than 1 stored.');
        $this->assertSame($callbacks[0], $MockCallback);
    }

    /**
     * Ensures that multiple, unique callbacks may be added to unique events and
     * that the appropriate callback collections are returned for those events.
     */
    public function testMediatorStoringMultipleCallbackToMultipleEvent() {
        $firstEventName = 'foo';
        $secondEventName = 'bar';
        $FirstMockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $FirstMockCallback->expects($this->exactly(2))
                          ->method('getEventName')
                          ->will($this->returnValue($firstEventName));
        $SecondMockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $SecondMockCallback->expects($this->exactly(2))
                           ->method('getEventName')
                           ->will($this->returnValue($secondEventName));

        $this->Registry->registerEvent($firstEventName, 'bar');
        $this->Registry->registerEvent($secondEventName, 'bar');
        $this->Mediator->addCallback($FirstMockCallback);
        $this->Mediator->addCallback($SecondMockCallback);
        $firstCallbacks = $this->Mediator->getCallbacks($firstEventName);
        $secondCallbacks = $this->Mediator->getCallbacks($secondEventName);

        $this->assertSame($firstCallbacks[0], $FirstMockCallback);
        $this->assertSame($secondCallbacks[0], $SecondMockCallback);
    }

    /**
     * Ensures that if an event is added to the Mediator that is not registered
     * appropriately an exception will be thrown.
     */
    public function testMediatorStoringInvalidCallbackThrowsException() {
        $eventName = 'nonexistent.event_name';
        $MockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $MockCallback->expects($this->once())
                     ->method('getEventName')
                     ->will($this->returnValue($eventName));

        $this->setExpectedException('\SprayFire\Mediator\Exception\EventNotRegistered');
        $this->Mediator->addCallback($MockCallback);
    }

    /**
     * Ensures that a valid callback that has been added to event collection may
     * also be removed from that collection.
     */
    public function testMediatorRemovingValidCallback() {
        $eventName = 'foo';
        $MockCallback = $this->getMock(
            '\\SprayFire\\Mediator\\Callback');
        $MockCallback->expects($this->exactly(4))
                     ->method('getEventName')
                     ->will($this->returnValue($eventName));
        $MockCallback->expects($this->once())
                     ->method('equals')
                     ->will($this->returnValue('same_mock'));

        $this->Registry->registerEvent($eventName, 'bar');
        $this->Mediator->addCallback($MockCallback);

        $this->assertCount(1, $this->Mediator->getCallbacks($eventName), 'An event does not have a stored callback though it should');
        $this->assertTrue($this->Mediator->removeCallback($MockCallback), 'An event has not been properly removed');
        $this->assertCount(0, $this->Mediator->getCallbacks($eventName), 'An event has a stored callback though it should not');
    }

    /**
     * Ensures that multiple events can have callbacks stored in their collection
     * and those callbacks are invoked when the appropriate event is triggered.
     */
    public function testMediatorTriggeringMultipleEvents() {
        $eventData = array();
        $firstEvent = 'foo';
        $secondEvent = 'bar';
        $function = function(SFMediator\Event $Event) use(&$eventData) {
            $eventName = $Event->getEventName();
            $eventData[$eventName] = 'value set';
        };
        $FirstMockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $FirstMockCallback->expects($this->exactly(2))
                          ->method('getEventName')
                          ->will($this->returnValue($firstEvent));

        $FirstMockCallback->expects($this->once())
                          ->method('invoke')
                          ->will($this->returnCallback($function));

        $SecondMockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $SecondMockCallback->expects($this->exactly(2))
                           ->method('getEventName')
                           ->will($this->returnValue($secondEvent));

        $SecondMockCallback->expects($this->once())
                           ->method('invoke')
                           ->will($this->returnCallback($function));

        $this->Registry->registerEvent($firstEvent, 'bar');
        $this->Registry->registerEvent($secondEvent, 'bar');
        $this->Mediator->addCallback($FirstMockCallback);
        $this->Mediator->addCallback($SecondMockCallback);
        $this->Mediator->triggerEvent($firstEvent, '');
        $this->Mediator->triggerEvent($secondEvent, '');
        $expected = array(
            $firstEvent => 'value set',
            $secondEvent => 'value set'
        );
        $this->assertSame($expected, $eventData);
    }

    /**
     * Ensures that an exception is thrown if an event is triggered that is not
     * appropriately registered.
     */
    public function testMediatorTriggerInvalidEventThrowsException() {
        $eventName = 'notarget.nonexistent';
        $Target = 'something';
        $this->setExpectedException('\SprayFire\Mediator\Exception\EventNotRegistered');
        $this->Mediator->triggerEvent($eventName, $Target);
    }

}
