<?php

/**
 * An interface to store callbacks against events and trigger events, invoking all
 * callbacks associated to that event.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator;

use \SprayFire\Mediator\Callback as Callback;

interface Mediator {

    /**
     * @param SprayFire.Mediator.Callback $Callback
     * @return boolean
     */
    public function addCallback(Callback $Callback);

    /**
     * @param SprayFire.Mediator.Callback $Callback
     */
    public function removeCallback(Callback $Callback);

    /**
     * @param string $eventName
     * @param Object $Target
     * @param array $arguments
     * @return boolean
     */
    public function triggerEvent($eventName, $Target, array $arguments = array());

}