<?php

/**
 * Testing the implementation of the \SprayFire\Mediator\FireMediator\EventStorage
 * object used by the \SprayFire\Mediator\FireMediator\Mediator and
 * \SprayFire\Mediator\FireMediator\EventRegistry objects to manage the storage
 * and retrieval of \SprayFire\Mediator\Event objects.
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
class EventStorageTest extends PHPUnitTestCase {

    /**
     * Ensures that the appropriate event container is created when createContainer
     * is invoked.
     */
    public function testCreatingNewEventContainer() {
        $EventStorage = new FireMediator\EventStorage();
        $EventStorage->createContainer('foo');
        $expected = array(
            'foo' => array()
        );
        $this->assertAttributeSame($expected, 'eventContainers', $EventStorage);
    }

    /**
     * Ensures that an event is added to a container that was just created.
     */
    public function testAddingEventToCreatedContainer() {
        $Event = $this->getMock('\SprayFire\Mediator\Event');
        $Event->expects($this->once())
              ->method('getName')
              ->will($this->returnValue('foo'));
        $EventStorage = new FireMediator\EventStorage();
        $EventStorage->createContainer('foo');
        $EventStorage->addEvent($Event);

        $expected = array(
            'foo' => array(
                $Event
            )
        );
        $this->assertAttributeSame($expected, 'eventContainers', $EventStorage);
    }

}
