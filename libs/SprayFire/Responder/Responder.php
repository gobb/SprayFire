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
     * Should retrieve the data set in the $Controller, sanitize it if necessary,
     * and then return a \SprayFire\Http\Response object that represents that
     * data.
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @return \SprayFire\Http\Response
     */
    public function generateResponse(Controller\Controller $Controller);

}
