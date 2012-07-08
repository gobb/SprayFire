<?php

/**
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Responder;

interface Responder {

    /**
     * Should return an array of snitized data based on the type of responder
     * being used.
     *
     * @param $data array
     * @return array
     */
    public function sanitizeData(array $data);

    /**
     * Should sanitize the appropriate data and generate a response based on the
     * $Controller data provided.
     *
     * @param $Controller SprayFire.Controller.Controller
     * @return string
     */
    public function generateResponse(\SprayFire\Controller\Controller $Controller);

}