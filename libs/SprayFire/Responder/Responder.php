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

use \SprayFire\Object,
    \SprayFire\Service,
    \SprayFire\Controller;

/**
 * @package SprayFire
 * @subpackage Responder.Interface
 */
interface Responder extends Object, Service\Consumer {

    /**
     * Should sanitize the appropriate data and generate a response based on the
     * $Controller data provided.
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @return string
     */
    public function generateDynamicResponse(Controller\Controller $Controller);

}
