<?php

/**
 * Interface to implement a Mediator design pattern to trigger various events and
 * invoke various functions attached to those events.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Mediator;

/**
 * @package SprayFire
 * @subpackage Mediator.Interface
 */
interface Mediator {

    /**
     * Should add a callback to a collection of callbacks for a specific event
     * based on the event name returned from the passed $Callback.
     *
     * @param \SprayFire\Mediator\Callback $Callback
     * @return boolean
     */
    public function addCallback(Callback $Callback);

    /**
     * Should remove a callback from the collection of callbacks for the event
     * name.
     *
     * @param \SprayFire\Mediator\Callback $Callback
     */
    public function removeCallback(Callback $Callback);

    /**
     * Should create an appropriate SprayFire.Mediator.Event object and invoke
     * the appropriate callbacks stored in the collection associated to $eventName.
     *
     * $Target and $arguments should be provided by the Event object created and
     * returned from their appropriate getter methods.
     *
     * @param string $eventName
     * @param object $Target
     * @param array $arguments
     * @return boolean
     */
    public function triggerEvent($eventName, $Target, array $arguments = []);

    /**
     * Will return an array of SprayFire.Mediator.Callback objects or an empty
     * array if no callbacks are added for the given event.
     *
     * @param string $eventName
     * @return array
     */
    public function getCallbacks($eventName);

}
