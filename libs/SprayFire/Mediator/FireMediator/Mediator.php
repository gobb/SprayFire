<?php

/**
 * An implementation of SprayFire.Mediator.Mediator used to handle event triggering
 * for the SprayFire framework and your application.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\CoreObject as SFCoreObject,
    \InvalidArgumentException as InvalidArgumentException;


/**
 * @package SprayFire
 * @subpackage Mediator.FireMediator
 *
 * @todo
 * We have a serious disconnect between this implementation and
 * \SprayFire\Mediator\FireMediator\EventRegistry.  We need to ensure the Mediator
 * is properly updated when events are registered and unregistered on the fly.
 *
 */
class Mediator extends SFCoreObject implements SFMediator\Mediator {

    /**
     * A multi-dimensional array that stores the callbacks for various events
     *
     * @property array
     */
    protected $eventCallbacks = array();

    /**
     * Stores the events that are able to be triggered by this implementation.
     *
     * @property \SprayFire\Mediator\FireMediator\EventRegistry
     */
    protected $Registry;

    /**
     * @param \SprayFire\Mediator\FireMediator\EventRegistry $Registry
     */
    public function __construct(EventRegistry $Registry) {
        $this->Registry = $Registry;
        $this->buildEventStorage();
    }

    /**
     * Will add the appropriate event storage containers to the primary event
     * callback array.
     */
    protected function buildEventStorage() {
        foreach ($this->Registry as $event => $eventType) {
            $this->eventCallbacks[$event] = array();
        }
    }

    /**
     * Will add the $Callback to be invoked when the $Callback::getEventName() is
     * triggered.
     *
     * @param \SprayFire\Mediator\Callback $Callback
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function addCallback(SFMediator\Callback $Callback) {
        $eventName = $Callback->getEventName();
        if (!$this->Registry->hasEvent($eventName)) {
            throw new InvalidArgumentException('The event name, ' . $eventName . ', is not valid and cannot be used as a callback.');
        }
        $this->eventCallbacks[$eventName][] = $Callback;
        return true;
    }

    /**
     * Will remove the $Callback associated to the $Callback::getEventName().
     *
     * This implementation uses $Callback::equals() from the SprayFire.Object
     * interface to ensure that the appropriate object is removed.
     *
     * @param \SprayFire\Mediator\Callback $Callback
     * @return boolean
     */
    public function removeCallback(SFMediator\Callback $Callback) {
        $eventName = $Callback->getEventName();
        if (!$this->Registry->hasEvent($eventName)) {
            return false;
        }
        $callbackRemoved = false;
        foreach($this->eventCallbacks[$eventName] as $key => $StoredCallback) {
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
     * If the $eventName is not a validly registered event an empty array will
     * be returned otherwise will return array of whatever callbacks are attached
     * to the $eventName.
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
     * @return void
     * @throws \InvalidArgumentException
     */
    public function triggerEvent($eventName, $Target, array $arguments = array()) {
        if (!$this->Registry->hasEvent($eventName)) {
            throw new InvalidArgumentException('The event name passed, ' . $eventName . ', is not a validly registered event.');
        }
        $Event = new Event($eventName, $Target, $arguments);
        $callbacks = $this->eventCallbacks[$eventName];
        foreach ($callbacks as $Callback) {
            $Callback->invoke($Event, $arguments);
        }
    }

}
