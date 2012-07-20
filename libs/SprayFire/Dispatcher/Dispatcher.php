<?php

/**
 * An interface responsible for taking a request, invoking whatever logic is associated
 * with that request and sending a response to the user.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Dispatcher;

use \SprayFire\Http\Request as Request;

interface Dispatcher {

    /**
     * @param SprayFire.Http.Request
     */
    public function dispatchResponse(Request $Request);

}