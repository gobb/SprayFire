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