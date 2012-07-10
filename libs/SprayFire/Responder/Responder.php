<?php

/**
 * An interface responsible for generating and sending a response based on a given
 * controller.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Responder;

use \SprayFire\Service\Consumer as ServiceConsumer;

interface Responder extends ServiceConsumer {

    /**
     * Should return an array of snitized data based on the type of responder
     * being used.
     *
     * @param array $data
     * @return array
     */
    public function sanitizeData(array $data);

    /**
     * Should sanitize the appropriate data and generate a response based on the
     * $Controller data provided.
     *
     * @param SprayFire.Controller.Controller $Controller
     * @return string
     */
    public function generateResponse(\SprayFire\Controller\Controller $Controller);

}