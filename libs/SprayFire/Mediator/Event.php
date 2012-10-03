<?php

/**
 * Interface representing an event that can be triggered by a SprayFire.Mediator.Mediator.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Mediator;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Mediator
 */
interface Event extends SFObject {

    /**
     * @return string
     */
    public function getEventName();

    /**
     * Should return the object of the target for the event.
     *
     * @return Object
     */
    public function getTarget();

    /**
     * Should return an array of arguments passed to the event triggering.
     *
     * @return array
     */
    public function getArguments();

}