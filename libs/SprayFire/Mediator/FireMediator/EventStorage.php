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
class EventStorage extends SFCoreObject {

    /**
     * Holds arrays of events with each array associated to a key that is the
     * event's name.
     *
     * @property array
     */
    protected $eventContainers = array();

    /**
     * @param string $eventName
     * @return void
     */
    public function createContainer($eventName) {
        if (!\array_key_exists($eventName, $this->eventContainers)) {
            $this->eventContainers[(string) $eventName] = array();
        }
    }

    /**
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function addEvent(SFMediator\Event $Event) {
        $this->eventContainers[$Event->getEventName()][] = $Event;
    }

}
