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
     * Holds arrays of events with each array associated to a key that is the
     * event's name.
     *
     * @property array
     */
    protected $callbackContainers = array();

    /**
     * @param string $eventName
     * @return void
     */
    public function createContainer($eventName) {
        if (!\array_key_exists($eventName, $this->callbackContainers)) {
            $this->callbackContainers[(string) $eventName] = array();
        }
    }

    /**
     * @param \SprayFire\Mediator\Callback $Callback
     * @return void
     */
    public function addCallback(SFMediator\Callback $Callback) {

    }

}
