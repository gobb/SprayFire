<?php

/**
 *
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
 *
 *
 * @package SprayFire
 * @subpackage
 */
class EventRegistryTest extends PHPUnitTestCase {

    protected $Storage;

    protected $Registry;

    public function setUp() {
        $this->Storage = new FireMediator\CallbackStorage();
        $this->Registry = new FireMediator\EventRegistry($this->Storage);
    }

    public function testAddingEventToRegistry() {
        $this->Registry->registerEvent('foo', 'bar');
        $this->assertTrue($this->Registry->hasEvent('foo'));
    }

    public function testGettingEventTypeAdded() {
        $this->Registry->registerEvent('foo', 'bar');
        $this->assertSame('bar', $this->Registry->getEventTargetType('foo'));
    }

    public function testGettingFalseOnNotAddedEvent() {
        $this->assertFalse($this->Registry->hasEvent('foo'));
    }

    public function testRemovingAddedEvent() {
        $this->Registry->registerEvent('foo', 'bar');

        $this->assertTrue($this->Registry->hasEvent('foo'));

        $this->Registry->unregisterEvent('foo');
        $this->assertFalse($this->Registry->hasEvent('foo'));
    }

    public function testThrowingExceptionRegisteringDuplicateEvent() {
        $this->Registry->registerEvent('foo', 'bar');

        $this->setExpectedException('\SprayFire\Mediator\Exception\DuplicateRegisteredEvent', 'The event, foo, has already been registered.');
        $this->Registry->registerEvent('foo', 'baz');
    }

    public function testGettingEventTargetTypeForUnregisteredEvent() {
        $this->assertFalse($this->Registry->getEventTargetType('foo'));
    }

    public function testEventStorageCreatesContainerOnEventRegistered() {
        $this->Registry->registerEvent('foo', 'bar');
        $expected = array(
            'foo' => array()
        );
        $this->assertAttributeSame($expected, 'callbackContainers', $this->Storage);
    }

}
