<?php

/**
 * An implementation of SprayFire.Mediator.Mediator used to handle event triggering
 * for the SprayFire framework and your application.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\StdLib;


/**
 * @package SprayFire
 * @subpackage Mediator.Implementation
 */
class Mediator extends StdLib\CoreObject implements SFMediator\Mediator {

    /**
     * A multi-dimensional array that stores the callbacks for various events
     *
     * @property array
     */
    protected $eventCallbacks = [];

    /**
     * Stores the events that are able to be triggered by this implementation.
     *
     * @property \SprayFire\Mediator\FireMediator\EventRegistry
     */
    protected $Registry;

    /**
     * @property \SprayFire\Mediator\FireMediator\CallbackStorage
     */
    protected $Storage;

    /**
     * @param \SprayFire\Mediator\FireMediator\EventRegistry $Registry
     */
    public function __construct(EventRegistry $Registry, CallbackStorage $Storage) {
        $this->Registry = $Registry;
        $this->Storage = $Storage;
    }

    /**
     * Will add the $Callback to be invoked when the $Callback::getEventName() is
     * triggered.
     *
     * @param \SprayFire\Mediator\Callback $Callback
     * @return boolean
     * @throws \SprayFire\Mediator\Exception\EventNotRegistered
     */
    public function addCallback(SFMediator\Callback $Callback) {
        $eventName = $Callback->getEventName();
        if (!$this->Registry->hasEvent($eventName)) {
            throw new SFMediator\Exception\EventNotRegistered('The event name, ' . $eventName . ', is not valid and cannot be used as a callback.');
        }
        $this->Storage->addCallback($Callback);
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
        return $this->Storage->removeCallback($Callback);
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
        return $this->Storage->getCallbacks($eventName);
    }

    /**
     * @param string $eventName
     * @param object $Target
     * @param array $arguments
     * @return void
     * @throws \SprayFire\Mediator\Exception\EventNotRegistered
     */
    public function triggerEvent($eventName, $Target, array $arguments = []) {
        if (!$this->Registry->hasEvent($eventName)) {
            throw new SFMediator\Exception\EventNotRegistered('The event name passed, ' . $eventName . ', is not a validly registered event.');
        }
        $Event = new Event($eventName, $Target, $arguments);
        foreach ($this->Storage->getCallbacks($eventName) as $Callback) {
            /** @var \SprayFire\Mediator\Callback $Callback */
            $Callback->invoke($Event);
        }
    }

}
