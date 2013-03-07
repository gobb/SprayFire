<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator\Event as MediatorEvent,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFire
 * @subpackage Mediator.FireMediator
 */
class Event extends SFStdLib\CoreObject implements MediatorEvent {

    protected $event;

    protected $Target;

    protected $arguments;

    public function __construct($event, $Target, array $arguments = []) {
        $this->event = $event;
        $this->Target = $Target;
        $this->arguments = $arguments;
    }

    public function getEventName() {
        return $this->event;
    }

    public function getTarget() {
        return $this->Target;
    }

    public function getArguments() {
        return $this->arguments;
    }


}
