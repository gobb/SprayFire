<?php

/**
 * An object that abstracts away invoking a function for a given event
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator\Callback as MediatorCallback,
    \SprayFire\Mediator\Event as MediatorEvent,
    \SprayFire\CoreObject as CoreObject;

class Callback extends CoreObject implements MediatorCallback {

    protected $eventName;

    public function __construct($eventName) {
        $this->eventName = $eventName;
    }


    public function getEventName() {
        return $this->eventName;
    }

    public function invoke(MediatorEvent $Event) {

    }

}