<?php

/**
 * Interface responsible for preparing and sending an appropriate response to
 * the user.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * code
 */

namespace SprayFire\Responder;

use \SprayFire\Object as SFObject,
    \SprayFire\Service as SFService,
    \SprayFire\Controller as SFController;

/**
 * @package SprayFire
 * @subpackage Responder
 */
interface Responder extends SFObject, SFService\Consumer {

    /**
     * Should sanitize the appropriate data and generate a response based on the
     * $Controller data provided.
     *
     * @param SprayFire.Controller.Controller $Controller
     * @return string
     */
    public function generateDynamicResponse(SFController\Controller $Controller);

}