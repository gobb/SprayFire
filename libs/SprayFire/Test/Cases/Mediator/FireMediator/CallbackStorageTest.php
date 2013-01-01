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

}
