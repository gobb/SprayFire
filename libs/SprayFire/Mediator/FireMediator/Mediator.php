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
    \SprayFire\CoreObject as CoreObject;


class Mediator extends CoreObject implements MediatorMediator {

    /**
     * @property array
     */
    protected $eventCallbacks = array();

    /**
     * @param SprayFire.Mediator.Callback $Callback
     * @return boolean
     */
    public function addCallback(MediatorCallback $Callback) {
        $this->eventCallbacks[] = $Callback;
        return true;
    }

    /**
     *
     * @param SprayFire.Mediator.Callback $Callback
     * @return boolean
     */
    public function removeCallback(MediatorCallback $Callback) {

    }

    /**
     *
     * @param string $eventName
     * @return array
     */
    public function getCallbacks($eventName) {
        return $this->eventCallbacks;
    }

    /**
     * @param string $eventName
     * @param object $Target
     * @param array $arguments
     */
    public function triggerEvent($eventName, $Target, array $arguments = array()) {

    }

}