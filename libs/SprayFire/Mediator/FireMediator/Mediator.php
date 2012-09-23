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
    \SprayFire\Mediator\FireMediator\Event as MediatorEvent,
    \SprayFire\Mediator\FireMediator\EventRegistry as EventRegistry,
    \SprayFire\CoreObject as CoreObject;

class Mediator extends CoreObject implements MediatorMediator {

    /**
     * @property array
     */
    protected $eventCallbacks = array();

    /**
     * @property SprayFire.Mediator.FireMediator.EventRegistry
     */
    protected $Registry;

    /**
     * @param SprayFire.Mediator.FireMediator.EventRegistry $Registry
     */
    public function __construct(EventRegistry $Registry) {
        $this->Registry = $Registry;
        $this->buildEventStorage();
    }

    public function buildEventStorage() {
        foreach ($this->Registry as $event => $eventType) {
            $this->eventCallbacks[$event] = array();
        }
    }

    /**
     * @param SprayFire.Mediator.Callback $Callback
     * @return boolean
     */
    public function addCallback(MediatorCallback $Callback) {
        $eventName = $Callback->getEventName();
        if (!$this->Registry->hasEvent($eventName)) {
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
        if (!$this->Registry->hasEvent($eventName)) {
            return false;
        }
        $storedCallbacks = $this->eventCallbacks[$eventName];
        $callbackRemoved = false;
        foreach($storedCallbacks as $key => $StoredCallback) {
            if ($Callback->equals($StoredCallback)) {
                $this->eventCallbacks[$eventName][$key] = null;
                unset($this->eventCallbacks[$eventName][$key]);
                $callbackRemoved = true;
                break;
            }
        }
        return $callbackRemoved;
    }

    /**
     *
     * @param string $eventName
     * @return array
     */
    public function getCallbacks($eventName) {
        if (!$this->Registry->hasEvent($eventName)) {
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
        if (!$this->Registry->hasEvent($eventName)) {
            throw new \InvalidArgumentException('The event name passed, ' . $eventName . ', is not a validly registered event.');
        }
        $Event = new MediatorEvent($eventName, $Target, $arguments);
        $callbacks = $this->eventCallbacks[$eventName];
        foreach ($callbacks as $Callback) {
            $Callback->invoke($Event, $arguments);
        }
    }

}