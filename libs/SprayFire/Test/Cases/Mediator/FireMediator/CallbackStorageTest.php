<?php

/**
 * Testing the implementation of the \SprayFire\Mediator\FireMediator\CallbackStorage
 * object used by the \SprayFire\Mediator\FireMediator\Mediator and
 * \SprayFire\Mediator\FireMediator\EventRegistry objects to manage the storage
 * and retrieval of \SprayFire\Mediator\Callback objects associated to a given event.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Test\Cases\Mediator\FireMediator;

use \SprayFire\Mediator\FireMediator as FireMediator,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Cases.Mediator.FireMediator
 */
class CallbackStorageTest extends PHPUnitTestCase {

    /**
     * Ensures that the appropriate callback container is created when createContainer
     * is invoked.
     */
    public function testCreatingNewCallbackContainer() {
        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->createContainer('foo');
        $expected = array(
            'foo' => array()
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);
    }

    /**
     * Ensures that a callback is added to a container that was just created.
     */
    public function testAddingCallbackToCreatedContainer() {
        $Callback = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback->expects($this->once())
              ->method('getEventName')
              ->will($this->returnValue('foo'));
        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->createContainer('foo');
        $CallbackStorage->addCallback($Callback);

        $expected = array(
            'foo' => array(
                $Callback
            )
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);
    }

    /**
     * Ensures that if a callback is added before container explicitly created that
     * SprayFire doesn't puke all over everything.
     */
    public function testAddingCallbackToContainerWhenContainerNotExplicitlyCreated() {
        $Callback = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback->expects($this->once())
              ->method('getEventName')
              ->will($this->returnValue('foo'));
        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->addCallback($Callback);

        $expected = array(
            'foo' => array(
                $Callback
            )
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);
    }

    /**
     * Ensures that an entire container is properly removed.
     */
    public function testRemovingContainerCreatedAndAddedTo() {
        $Callback = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback->expects($this->once())
                 ->method('getEventName')
                 ->will($this->returnValue('foo'));
        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->addCallback($Callback);

        $expected = array(
            'foo' => array(
                $Callback
            )
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);

        $CallbackStorage->removeContainer('foo');
        $expected = array();
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);
    }

    public function testRemovingSpecificCallbackWithoutEffectingRestOfContainer() {
        $Callback1 = $this->getMock('\SprayFire\Mediator\Callback');
        // This will be called the second time when we remove the callback
        $Callback1->expects($this->exactly(2))
                  ->method('getEventName')
                  ->will($this->returnValue('foo'));
        $Callback1->expects($this->once())
                  ->method('equals')
                  ->with($Callback1)
                  ->will($this->returnValue(true));
        $Callback2 = $this->getMock('\SprayFire\Mediator\Callback');
        // This doesn't get removed so only will be called once
        $Callback2->expects($this->once())
                  ->method('getEventName')
                  ->will($this->returnValue('foo'));

        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->addCallback($Callback1);
        $CallbackStorage->addCallback($Callback2);

        $expected = array(
            'foo' => array(
                $Callback1,
                $Callback2
            )
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);

        $this->assertTrue($CallbackStorage->removeCallback($Callback1));

        $expected = array(
            'foo' => array(
                1 => $Callback2
            )
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $CallbackStorage);
    }

    public function testReturningFalseWhenRemovingCallbackThatHasNoContainer() {
        $Callback = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback->expects($this->once())
                 ->method('getEventName')
                 ->will($this->returnValue('foo'));
        $CallbackStorage = new FireMediator\CallbackStorage();

        $this->assertFalse($CallbackStorage->removeCallback($Callback));
    }

    public function testReturningFalseWhenRemovingCallbackWithContainerButNotAdded() {
        $Callback = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback->expects($this->once())
                 ->method('getEventName')
                 ->will($this->returnValue('foo'));

        $RemoveCallback = $this->getMock('\SprayFire\Mediator\Callback');
        $RemoveCallback->expects($this->once())
                       ->method('getEventName')
                       ->will($this->returnValue('foo'));
        $RemoveCallback->expects($this->once())
                       ->method('equals')
                       ->with($Callback)
                       ->will($this->returnValue(false));

        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->addCallback($Callback);

        $this->assertFalse($CallbackStorage->removeCallback($RemoveCallback));
    }

    public function testReturningCallbacksForEvent() {
        $Callback1 = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback1->expects($this->once())
                  ->method('getEventName')
                  ->will($this->returnValue('foo'));
        $Callback2 = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback2->expects($this->once())
                  ->method('getEventName')
                  ->will($this->returnValue('foo'));
        $Callback3 = $this->getMock('\SprayFire\Mediator\Callback');
        $Callback3->expects($this->once())
                  ->method('getEventName')
                  ->will($this->returnValue('bar'));

        $CallbackStorage = new FireMediator\CallbackStorage();
        $CallbackStorage->addCallback($Callback1);
        $CallbackStorage->addCallback($Callback2);
        $CallbackStorage->addCallback($Callback3);

        $expectedFoo = array(
            $Callback1,
            $Callback2
        );
        $expectedBar = array(
            $Callback3
        );

        $this->assertSame($expectedFoo, $CallbackStorage->getCallbacks('foo'));
        $this->assertSame($expectedBar, $CallbackStorage->getCallbacks('bar'));
    }
}
