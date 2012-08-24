<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Mediator;

abstract class DispatcherEvents {

    const BEFORE_ROUTING = 'request.before_routing';

    const AFTER_ROUTING = 'routedrequest.after_routing';

    const BEFORE_CONTROLLER_INVOKED = 'controller.before_invoked';

    const AFTER_CONTROLLER_INVOKED = 'controller.after_invoked';

    const BEFORE_RESPONSE_SENT = 'responder.before_responder_sent';

    const AFTER_RESPONSE_SENT = 'responder.after_responder_sent';

}