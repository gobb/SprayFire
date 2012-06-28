<?php

/**
 * An interface that represents an HTTP request sent to the app
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Http;

interface Request {

    /**
     * @return SprayFire.Http.Uri
     */
    public function getUri();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return float
     */
    public function getVersion();

}