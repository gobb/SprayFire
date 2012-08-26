<?php

/**
 * An implementation of SprayFire.Mediator.Mediator used to handle event triggering
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator\Mediator as MediatorMediator,
    \SprayFire\Mediator\Callback as MediatorCallback,
    \SprayFire\Mediator\DispatcherEvents as DispatcherEvents,
    \SprayFire\CoreObject as CoreObject;

class Mediator extends CoreObject implements MediatorMediator {

    /**
     * @property array
     */
    protected $eventCallbacks = array();

    /**
     * @property array
     */
    protected $validCallbacks = array();

    public function __construct() {
        $this->buildValidEventCallbacks();
        $this->buildEventStorage();
    }

    /**
     * Will create a map of valid callbacks based on the abstract class constants
     * for event classes.
     */
    protected function buildValidEventCallbacks() {
        $eventClasses = array(
            '\\SprayFire\\Mediator\\DispatcherEvents'
        );
        foreach ($eventClasses as $eventClass) {
            $ReflectedEvent = new \ReflectionClass($eventClass);
            $eventContants = $ReflectedEvent->getConstants();
            $this->validCallbacks = \array_merge($this->validCallbacks, $eventContants);
        }
    }

    protected function buildEventStorage() {
        foreach ($this->validCallbacks as $eventName) {
            $this->eventCallbacks[$eventName] = array();
        }
    }

    /**
     * @param SprayFire.Mediator.Callback $Callback
     * @return boolean
     */
    public function addCallback(MediatorCallback $Callback) {
        $eventName = $Callback->getEventName();
        if (!\in_array($eventName, $this->validCallbacks)) {
            throw new \InvalidArgumentException('The event name, ' . $eventName . ', is not valid and cannot be used as a callback.');
        }
        $this->eventCallbacks[$eventName][] = $Callback;
        return true;
    }

    /**
     *
     * @param SprayFire.Mediator.Callback $Callback
     * @return boolean
     */
    public function removeCallback(MediatorCallback $Callback) {
        $eventName = $Callback->getEventName();
        if (!\in_array($eventName, $this->validCallbacks)) {
            return false;
        }
        $storedCallbacks = $this->eventCallbacks[$eventName];
        foreach($storedCallbacks as $key => $StoredCallback) {
            if ($Callback->equals($StoredCallback)) {
                unset($this->eventCallbacks[$eventName][$key]);
            }
        }
        return true;
    }

    /**
     *
     * @param string $eventName
     * @return array
     */
    public function getCallbacks($eventName) {
        if (!\in_array($eventName, $this->validCallbacks)) {
            return array();
        }
        return $this->eventCallbacks[$eventName];
    }

    /**
     * @param string $eventName
     * @param object $Target
     * @param array $arguments
     */
    public function triggerEvent($eventName, $Target, array $arguments = array()) {
        if (!\in_array($eventName, $this->validCallbacks)) {
            throw new \InvalidArgumentException('The event name passed, ' . $eventName . ', is not a validly registered event.');
        }
        $Event = new \SprayFire\Mediator\FireMediator\Event($eventName, $Target, $arguments);
        $callbacks = $this->eventCallbacks[$eventName];
        foreach ($callbacks as $Callback) {
            $Callback->invoke($Event, $arguments);
        }
    }

}