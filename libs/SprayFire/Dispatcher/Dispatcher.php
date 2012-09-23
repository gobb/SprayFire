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

use \SprayFire\Object as Object,
    \SprayFire\Http\Request as Request;

interface Dispatcher extends Object {

    /**
     * At some point during the execution of this method the response appropriate
     * for the passed $Request should be sent to the user.
     *
     * @param SprayFire.Http.Request $Request
     * @return mixed
     */
    public function dispatchResponse(Request $Request);

}