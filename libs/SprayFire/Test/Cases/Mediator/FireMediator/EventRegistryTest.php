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

    public function testAddingEventToRegistry() {
        $Registry = new FireMediator\EventRegistry();
        $Registry->registerEvent('foo', 'bar');
        $this->assertTrue($Registry->hasEvent('foo'));
    }

    public function testGettingEventTypeAdded() {
        $Registry = new FireMediator\EventRegistry();
        $Registry->registerEvent('foo', 'bar');
        $this->assertSame('bar', $Registry->getEventTargetType('foo'));
    }

    public function testGettingFalseOnNotAddedEvent() {
        $Registry = new FireMediator\EventRegistry();
        $this->assertFalse($Registry->hasEvent('foo'));
    }

    public function testRemovingAddedEvent() {
        $Registry = new FireMediator\EventRegistry();
        $Registry->registerEvent('foo', 'bar');

        $this->assertTrue($Registry->hasEvent('foo'));

        $Registry->unregisterEvent('foo');
        $this->assertFalse($Registry->hasEvent('foo'));
    }

    public function testThrowingExceptionRegisteringDuplicateEvent() {
        $Registry = new FireMediator\EventRegistry();
        $Registry->registerEvent('foo', 'bar');

        $this->setExpectedException('\SprayFire\Mediator\Exception\DuplicateRegisteredEvent', 'The event, foo, has already been registered.');
        $Registry->registerEvent('foo', 'baz');
    }

    public function testGettingEventTargetTypeForUnregisteredEvent() {
        $Registry = new FireMediator\EventRegistry();
        $this->assertFalse($Registry->getEventTargetType('foo'));
    }

}
