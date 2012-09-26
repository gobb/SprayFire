<?php

/**
 * Interface for invoking the appropriate steps to return a response to a given
 * SprayFire.Http.Request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Object as SFObject,
    \SprayFire\Http as SFHttp;

/**
 * Implementations of this interface should be able to handle routing the given
 * SprayFire.Http.Request, creation and invocation of SprayFire.Controller.Controller,
 * creation of SprayFire.Responder.Responder and the sending of the response to
 * the user.
 *
 * It is also encouraged that your implementations support the SprayFire event
 * hooks we have built into the framework.  Please see the SprayFire.Mediator
 * module and also take a look at the wiki for more information on SprayFire events.
 *
 * @package SprayFire
 * @subpackage Dispatcher
 */
interface Dispatcher extends SFObject {

    /**
     * At some point during the execution of this method the response appropriate
     * for the passed $Request should be sent to the user.
     *
     * @param SprayFire.Http.Request $Request
     * @return mixed
     */
    public function dispatchResponse(SFHttp\Request $Request);

}