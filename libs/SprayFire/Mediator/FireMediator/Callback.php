<?php

/**
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

    public function getEventName() {

    }

    public function invoke(MediatorEvent $Event) {

    }

}