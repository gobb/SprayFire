<?php

/**
 * Interface to abstract the invocation of event callbacks by SprayFire.Mediator.Mediator
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Mediator;

use \SprayFire\Object as SFObject;

/**
 *
 * @package SprayFire
 * @subpackage Mediator
 */
interface Callback extends SFObject {

    /**
     * Should be the name of the event that this callback should be invoked for.
     *
     * @return string|array
     */
    public function getEventName();

    /**
     * Invoke whatever function is being stored in the callback
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function invoke(Event $Event);

}
