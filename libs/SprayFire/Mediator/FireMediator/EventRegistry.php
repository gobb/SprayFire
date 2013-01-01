<?php

/**
 * A registry of events used by \SprayFire\Mediator\FireMediator\Mediator to determine
 * what events should store callbacks and trigger events.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator\Exception as SFMediatorException,
    \SprayFire\CoreObject as SFCoreObject,
    \ArrayIterator as ArrayIterator,
    \IteratorAggregate as IteratorAggregate;

/**
 * This is a private package implementation that is designed to be used by the
 * SprayFire.Mediator.FireMediator implementations.
 *
 * This object is provided as a default service by SprayFire and you can add your
 * own custom events to this object in your application's bootstraps.
 *
 * @package SprayFire
 * @subpackage Mediator.FireMediator
 *
 * @todo
 * We need to take a look at some way we can hook into this object from SprayFire.Mediator.Mediator
 * so that we may take the appropriate steps when events are removed and added
 * to the registry.
 */
class EventRegistry extends SFCoreObject implements IteratorAggregate {

    /**
     * Key value array storing [$eventName => $targetType]
     *
     * @property array
     */
    protected $registry = array();

    /**
     * Adds an event and expected target type to the registry.
     *
     * An exception is thrown if the $eventName has already been registered.
     *
     * @param string $eventName
     * @param string $targetType Java or PHP style class name
     * @return void
     * @throws \SprayFire\Mediator\Exception\DuplicateRegisteredEvent
     */
    public function registerEvent($eventName, $targetType) {
        if ($this->hasEvent($eventName)) {
            throw new SFMediatorException\DuplicateRegisteredEvent('The event, ' . $eventName . ', has already been registered.');
        }
        $this->registry[$eventName] = $targetType;
    }

    /**
     * Removes an event from the registry, if that event has already been registered.
     *
     * @param string $eventName
     * @return void
     */
    public function unregisterEvent($eventName) {
        if ($this->hasEvent($eventName)) {
            $this->registry[$eventName] = null;
            unset($this->registry[$eventName]);
        }
    }

    /**
     * Will return a Java style or PHP style name, depending on the type of value
     * passed in the constructor of this implementation.
     *
     * @param string $eventName
     * @return string|false
     */
    public function getEventTargetType($eventName) {
        if ($this->hasEvent($eventName)) {
            return $this->registry[$eventName];
        }
        return false;
    }

    /**
     * Returns whether or not the event has been registered.
     *
     * @param string $eventName
     * @return boolean
     */
    public function hasEvent($eventName) {
        return \array_key_exists($eventName, $this->registry);
    }

    /**
     * Allows the registry to be iterated over.
     *
     * @return Traversable
     */
    public function getIterator() {
        return new ArrayIterator($this->registry);
    }

}
