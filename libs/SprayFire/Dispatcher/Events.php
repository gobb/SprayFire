<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher;

/**
 *
 *
 * @package SprayFire
 * @subpackage`
 */
abstract class Events {

    /**
     * Should be invoked before the routed controller is invoked and should set
     * the target as a SprayFire.Controller.Controller.
     */
    const BEFORE_CONTROLLER_INVOKED = 'controller.before_invoked';

    /**
     * Should be invoked after the routed controller is invoked and should set
     * the target as a SprayFire.Controller.Controller.
     */
    const AFTER_CONTROLLER_INVOKED = 'controller.after_invoked';

    /**
     * Should be invoked before a responder has generated and sent the response
     * with the SprayFire.Responder.Responder being used set as the target.
     */
    const BEFORE_RESPONSE_SENT = 'responder.before_responder_sent';

    /**
     * Should be invoked after a responder has generated and sent the response
     * with the SprayFire.Responder.Responder that was used set as the target.
     */
    const AFTER_RESPONSE_SENT = 'responder.after_responder_sent';

}
