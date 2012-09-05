<?php

/**
 * Holds events that the SprayFire.Mediator.FireMediator.Mediator is able to trigger.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\CoreObject as CoreObject;

class EventRegistry extends CoreObject {

    /**
     * @property array
     */
    protected $registry = array();

    /**
     * Adds an event to the registry, enforcing a specific type to be implemented
     * for the target of that event.
     *
     * An exception is thrown if the $eventName being registered has already been
     *
     *
     * @param string $eventName
     * @param string $targetType Java or PHP style class name
     * @return void
     * @throws InvalidArgumentException
     */
    public function registerEvent($eventName, $targetType) {
        if ($this->hasEvent($eventName)) {
            throw new \InvalidArgumentException('The event, ' . $eventName . ', has already been registered.');
        }
        $this->registry[$eventName] = $targetType;
    }

    /**
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
     * @param string $eventName
     * @return boolean
     */
    public function hasEvent($eventName) {
        return \array_key_exists($eventName, $this->registry);
    }

}