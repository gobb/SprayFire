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
    protected $function;

    public function __construct($eventName, $function) {
        $this->eventName = $eventName;
        if (!\is_callable($function)) {
            throw new \InvalidArgumentException('A callable function must be passed to \\SprayFire\\Mediator\\FireMediator\\Callback as the second constructor parameter.');
        }
        $this->function = $function;
    }


    public function getEventName() {
        return $this->eventName;
    }

    public function invoke(MediatorEvent $Event) {
        return \call_user_func_array($this->function, array($Event));
    }

}