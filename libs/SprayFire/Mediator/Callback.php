<?php

/**
 * An interface for objects representing a callback to invoke when a specific
 * event is triggered by SprayFire.Mediator.Mediator
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator;

use \SprayFire\Mediator\Event as Event,
    \SprayFire\Mediator\Object as Object;

interface Callback extends Object {

    /**
     * Should be the name of the event that this callback should be invoked for.
     *
     * Alternatively you can return an array of event names that should be triggered
     * if this particular callback is to be used for more than one event.
     *
     * @return string|array
     */
    public function getEventName();

    /**
     * Invoke whatever callback is required given the
     *
     * @param SprayFire.Mediator.Event $Event
     * @return void
     */
    public function invoke(Event $Event);



}