<?php

/**
 * An interface representing an event triggered by the SprayFire.Mediator.Mediator
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator;

use \SprayFire\Object as Object;

interface Event extends Object {

    /**
     * @return string
     */
    public function getEventName();

    /**
     * @return Object
     */
    public function getTarget();

    /**
     * @return array
     */
    public function getArguments();

}