<?php

/**
 * A module specific implementation used by the \SprayFire\Mediator\FireMediator\Mediator
 * to store and retrieve \SprayFire\Mediator\Event objects associated to the given
 * Event's name.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\CoreObject as SFCoreObject;

/**
 *
 *
 * @package SprayFire
 * @subpackage Mediator.FireMediator
 */
class CallbackStorage extends SFCoreObject {

    /**
     * Holds arrays of callbacks with each array associated to a key that is the
     * event's name.
     *
     * @property array
     */
    protected $callbackContainers = array();

    /**
     * @param string $eventName
     * @return boolean
     */
    protected function hasContainer($eventName) {
        return \array_key_exists($eventName, $this->callbackContainers);
    }

    /**
     * @param string $eventName
     * @return void
     */
    public function createContainer($eventName) {
        if (!$this->hasContainer($eventName)) {
            $this->callbackContainers[(string) $eventName] = array();
        }
    }

    /**
     * @param \SprayFire\Mediator\Callback $Callback
     * @return void
     */
    public function addCallback(SFMediator\Callback $Callback) {
        $this->callbackContainers[$Callback->getEventName()][] = $Callback;
    }

    /**
     * Note that this will remove the entire container and all callbacks associated
     * to the $eventName.
     *
     * @param string $eventName
     * @return void
     */
    public function removeContainer($eventName) {
        if ($this->hasContainer($eventName)) {
            unset($this->callbackContainers[$eventName]);
        }
    }

    /**
     * Adding code to remove a specific callback associated to an event name.
     *
     * @param \SprayFire\Mediator\Callback $Callback
     * @return boolean
     */
    public function removeCallback(SFMediator\Callback $Callback) {
        $eventName = $Callback->getEventName();
        if ($this->hasContainer($eventName)) {
            foreach ($this->callbackContainers[$eventName] as $key => $StoredCallback) {
                if ($Callback->equals($StoredCallback)) {
                    unset($this->callbackContainers[$eventName][$key]);
                    break;
                }
            }
        }
        return true;
    }

}
