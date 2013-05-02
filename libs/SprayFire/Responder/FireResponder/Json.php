<?php

/**
 * An implementation of SprayFire.Responder.Responder that will generate a JSON
 * response for a given request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder,
    \SprayFire\Controller;

/**
 * @package SprayFire
 * @subpackage Responder.Implementation
 */
class Json extends Base implements Responder\Responder {

    /**
     *
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @return string
     */
    public function generateResponse(Controller\Controller $Controller) {
        $data = $this->getEscapedData($Controller);
        $this->Response->setBody(\json_encode($data));
        return $this->Response;
    }

}
