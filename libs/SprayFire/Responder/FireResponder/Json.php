<?php

/**
 * An implementation of SprayFire.Responder.Responder that will generate a JSON
 * response for a given request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder as SFResponder,
    \SprayFire\Controller as SFController,
    \SprayFire\Service\FireService as FireService;

/**
 * @package SprayFire
 * @subpackage Responder.FireResponder
 */
class Json extends Base implements SFResponder\Responder {

    /**
     * 
     *
     * @param \SprayFire\Controller\Controller $Controller
     */
    public function generateDynamicResponse(SFController\Controller $Controller) {
        $data = $this->getEscapedData($Controller);
        echo \json_encode($data);
    }

}
