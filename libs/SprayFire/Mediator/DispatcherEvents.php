<?php

/**
 * Abstract classes storing the events that are invoked by a SprayFire.Dispatcher.Dispatcher.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Mediator;

/**
 * @package SprayFire
 * @subpackage Mediator
 *
 * @todo
 * We should take a look at moving this class to be under the SprayFire.Dispatcher
 * namespace and not SprayFire.Mediator.  It does not make sense that the Mediator
 * namespace would know about the details of the events.
 */
abstract class DispatcherEvents {

    /**
     * Should be invoked before any routing takes place and should set the target
     * as a SprayFire.Http.Request.
     */
    const BEFORE_ROUTING = 'request.before_routing';

    /**
     * Should be invoked after any routing takes place and should set the target
     * as a SprayFire.Http.Routing.RoutedRequest.
     */
    const AFTER_ROUTING = 'routedrequest.after_routing';

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
     * Should be invoked before a responder has generatd and sent the response
     * with the SprayFire.Responder.Responder being used set as the target.
     */
    const BEFORE_RESPONSE_SENT = 'responder.before_responder_sent';

    /**
     * Should be invoked after a responder has generated and sent the response
     * with the SprayFire.Responder.Responder that was used set as the target.
     */
    const AFTER_RESPONSE_SENT = 'responder.after_responder_sent';

}