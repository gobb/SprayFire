<?php

/**
 * Interface for invoking the appropriate steps to generate the response for a
 * resource represented by the information from a \SprayFire\Routing\RoutedRequest.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Object,
    \SprayFire\Routing;

/**
 * Implementations of this interface should be able to create and invoke a
 * \SprayFire\Controller\Controller, as represented by a \SprayFire\Routing\RoutedRequest,
 * generate the Responder necessary and finally send off the Response.
 *
 * It is also encouraged that your implementations support the SprayFire event
 * hooks we have built into the framework.  Please see the Mediator module.
 *
 * @package SprayFire
 * @subpackage Dispatcher.Interface
 */
interface Dispatcher extends Object {

    /**
     * At some point during the execution of this method the response appropriate
     * for the passed $Request should be sent to the user.
     *
     * @param \SprayFire\Routing\RoutedRequest $RoutedRequest
     * @return mixed
     */
    public function dispatchResponse(Routing\RoutedRequest $RoutedRequest);

}
