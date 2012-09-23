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

use \SprayFire\Mediator\Event as MediatorEvent,
    \SprayFire\Mediator\Callback as MediatorCallback,
    \SprayFire\Mediator\DispatcherEvents as DispatcherEvents,
    \SprayFire\Mediator\FireMediator\Mediator as FireMediator;

/**
 * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator
 */
class MediatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that a single callback can be added to an event and the appropriate
     * callback is returned as the only object in the events collection.
     *
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::addCallback
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::getCallbacks
     */
    public function testMediatorStoringSingleCallbackToSingleEvent() {
        $eventName = DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $MockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $MockCallback->expects($this->once())
                     ->method('getEventName')
                     ->will($this->returnValue($eventName));
        $Mediator = new FireMediator($this->getEventRegistry());

        $Mediator->addCallback($MockCallback);
        $callbacks = $Mediator->getCallbacks($eventName);

        $this->assertCount(1, $callbacks, 'Mediator that should have 1 callback has more or less than 1 stored.');
        $this->assertSame($callbacks[0], $MockCallback);
    }

    /**
     * Ensures that multiple, unique callbacks may be added to unique events and
     * that the appropriate callback collections are returned for those events.
     *
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::addCallback
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::getCallbacks
     */
    public function testMediatorStoringMultipleCallbackToMultipleEvent() {
        // setup
        $firstEventName = DispatcherEvents::BEFORE_CONTROLLER_INVOKED;
        $secondEventName = DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $FirstMockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $FirstMockCallback->expects($this->once())
                          ->method('getEventName')
                          ->will($this->returnValue($firstEventName));
        $SecondMockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $SecondMockCallback->expects($this->once())
                           ->method('getEventName')
                           ->will($this->returnValue($secondEventName));
        $Mediator = new FireMediator($this->getEventRegistry());

        $Mediator->addCallback($FirstMockCallback);
        $Mediator->addCallback($SecondMockCallback);
        $firstCallbacks = $Mediator->getCallbacks($firstEventName);
        $secondCallbacks = $Mediator->getCallbacks($secondEventName);

        $this->assertSame($firstCallbacks[0], $FirstMockCallback);
        $this->assertSame($secondCallbacks[0], $SecondMockCallback);
    }

    /**
     * Ensures that if an event is added to the Mediator that is not registered
     * appropriately an exception will be thrown.
     *
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::addCallback
     */
    public function testMediatorStoringInvalidCallbackThrowsException() {
        $eventName = 'nonexistent.event_name';
        $MockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $MockCallback->expects($this->once())
                     ->method('getEventName')
                     ->will($this->returnValue($eventName));
        $Mediator = new FireMediator($this->getEventRegistry());

        $this->setExpectedException('\\InvalidArgumentException');

        $Mediator->addCallback($MockCallback);
    }

    /**
     * Ensures that a valid callback that has been added to event collection may
     * also be removed from that collection.
     *
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::removeCallback
     */
    public function testMediatorRemovingValidCallback() {
        $eventName = DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $MockCallback = $this->getMock('\\SprayFire\\Mediator\\Callback');
        $MockCallback->expects($this->exactly(2))
                     ->method('getEventName')
                     ->will($this->returnValue($eventName));
        $MockCallback->expects($this->once())
                     ->method('equals')
                     ->will($this->returnValue('same_mock'));

        $Mediator = new FireMediator($this->getEventRegistry());
        $Mediator->addCallback($MockCallback);

        $this->assertCount(1, $Mediator->getCallbacks($eventName), 'An event does not have a stored callback though it should');
        $this->assertTrue($Mediator->removeCallback($MockCallback), 'An event has not been properly removed');
        $this->assertCount(0, $Mediator->getCallbacks($eventName), 'An event has a stored callback though it should not');
    }

    /**
     * Ensures that multiple events can have callbacks stored in their collection
     * and those callbacks are invoked when the appropriate event is triggered.
     *
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::triggerEvent
     */
    public function testMediatorTriggeringMultipleEvents() {
        $eventData = array();
        $firstEvent = DispatcherEvents::AFTER_CONTROLLER_INVOKED;
        $secondEvent = DispatcherEvents::BEFORE_CONTROLLER_INVOKED;
        $function = function(MediatorEvent $Event) use(&$eventData) {
            $eventName = $Event->getEventName();
            $eventData[$eventName] = 'value set';
        };
        $FirstMockCallback = $this->getMock(
            '\\SprayFire\\Test\\Cases\\Mediator\\FireMediator\\CallbackStub',
            array(
                'equals',
                'hashCode',
                '__toString',
                'getEventName',
            ),
            array(
                $function
            )
        );
        $FirstMockCallback->expects($this->once())
                          ->method('getEventName')
                          ->will($this->returnValue($firstEvent));

        $SecondMockCallback = $this->getMock(
            '\\SprayFire\\Test\\Cases\\Mediator\\FireMediator\\CallbackStub',
            array(
                'equals',
                'hashCode',
                '__toString',
                'getEventName'
            ),
            array(
                $function
            )
        );
        $SecondMockCallback->expects($this->once())
                           ->method('getEventName')
                           ->will($this->returnValue($secondEvent));

        $Mediator = new FireMediator($this->getEventRegistry());
        $Mediator->addCallback($FirstMockCallback);
        $Mediator->addCallback($SecondMockCallback);
        $Mediator->triggerEvent($firstEvent, '');
        $Mediator->triggerEvent($secondEvent, '');
        $expected = array(
            $firstEvent => 'value set',
            $secondEvent => 'value set'
        );
        $this->assertSame($expected, $eventData);
    }

    /**
     * Ensures that an exception is thrown if an event is triggered that is not
     * appropriately registered.
     *
     * @covers \\SprayFire\\Mediator\\FireMediator\\Mediator::triggerEvent
     */
    public function testMediatorTriggerInvalidEventThrowsException() {
        $eventName = 'notarget.nonexistent';
        $Target = 'something';
        $Mediator = new FIreMediator($this->getEventRegistry());
        $this->setExpectedException('\\InvalidArgumentException');
        $Mediator->triggerEvent($eventName, $Target);
    }

    /**
     * Returns an EventRegistry object with the appropriate events used in this
     * test case already registered.
     *
     * @return SprayFire.Mediator.FireMediator.EventRegistry
     */
    protected function getEventRegistry() {
        $Registry = new \SprayFire\Mediator\FireMediator\EventRegistry();

        $targetType = '';

        $Registry->registerEvent(DispatcherEvents::BEFORE_ROUTING, $targetType);
        $Registry->registerEvent(DispatcherEvents::AFTER_CONTROLLER_INVOKED, $targetType);
        $Registry->registerEvent(DispatcherEvents::BEFORE_CONTROLLER_INVOKED, $targetType);

        return $Registry;
    }

}

/**
 * A stub to allow the storing and invocation of callable variables.
 *
 * This stub is here to ensure that appropriate events are triggered as we expect
 * by the SprayFire.Mediator.FireMediator.Mediator
 */
abstract class CallbackStub implements MediatorCallback {

    /**
     * @property callable
     */
    protected $function;

    /**
     * @param callable $function
     */
    public function __construct($function) {
        $this->function = $function;
    }

    /**
     * @param SprayFire.Mediator.Event $Event
     * @return mixed
     */
    public function invoke(MediatorEvent $Event) {
        return \call_user_func_array($this->function, array($Event));
    }

}
