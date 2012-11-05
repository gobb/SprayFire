<?php

/**
 * A test case to ensure the coverage of SprayFire.Mediator.FireMediator.Callback
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Mediator\FireMediator;

use \SprayFire\Mediator\Event as MediatorEvent,
    \SprayFire\Mediator\FireMediator\Callback as FireCallback;

class CallbackTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensure that an anonymous function is properly invoked and that the appropriate
     * messages are passed to the anonymous function.
     */
    public function testCallbackInvokingAnonymousFunction() {
        $testData = array();
        $function = function(MediatorEvent $Event) use(&$testData) {
            $testData['eventName'] = $Event->getEventName();
            $testData['target'] = $Event->getTarget();
            $testData['arguments'] = $Event->getArguments();
        };

        $eventName = 'foo';
        $target = 'test';
        $arguments = array(1,2,3,4);

        $Callback = new FireCallback($eventName, $function);
        $MockEvent = $this->getMockedEvent($eventName, $target, $arguments);

        $Callback->invoke($MockEvent);

        $this->assertSame($eventName, $testData['eventName']);
        $this->assertSame($target, $testData['target']);
        $this->assertSame($arguments, $testData['arguments']);
    }

    /**
     * Ensure that a global function string name passed will have the appropriate
     * function invoked and the appropriate messages are passed.
     */
    public function testCallbackInvokingFunctionName() {
        $testData = array();
        $eventName = 'foo';
        $function = '\\SprayFire\\Test\\Cases\\Mediator\\FireMediator\\testData';
        $target = 'test';
        $arguments = array(&$testData,2,3,4);

        $Callback = new FireCallback($eventName, $function);
        $MockEvent = $this->getMockedEvent($eventName, $target, $arguments);

        $Callback->invoke($MockEvent);

        $this->assertSame($target, $testData['target']);
        $this->assertSame($eventName, $testData['eventName']);
    }

    /**
     * Will create a mock object of SprayFire.Mediator.Event interface, ensuring
     * that the appropriate methods from that interface are invoked one time with
     * the appropriate return values.
     *
     * @param string $name
     * @param mixed $target
     * @param array $arguments
     * @return SprayFire.Mediator.Event
     */
    protected function getMockedEvent($name, $target, array $arguments) {
        $mockedInterface = '\\SprayFire\\Mediator\\Event';
        $MockEvent = $this->getMock($mockedInterface);
        $MockEvent->expects($this->once())
                  ->method('getEventName')
                  ->will($this->returnValue($name));
        $MockEvent->expects($this->once())
                  ->method('getTarget')
                  ->will($this->returnValue($target));
        $MockEvent->expects($this->once())
                  ->method('getArguments')
                  ->will($this->returnValue($arguments));
        return $MockEvent;
    }

}

/**
 * Will store information about the event in an array that is the first argument
 * passed to the event.
 *
 * @param SprayFire.Mediator.Event $Event
 */
function testData(MediatorEvent $Event) {
    $arguments = $Event->getArguments();
    // This is a reference so we can pass along the appropriate keys we add to
    // $array in the code below.
    $array =& $arguments[0];
    $array['target'] = $Event->getTarget();
    $array['eventName'] = $Event->getEventName();
}