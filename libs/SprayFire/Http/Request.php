<?php

/**
 * An interface that represents an HTTP request sent to the app
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http;

interface Request {

    /**
     * @return SprayFire.Http.Uri
     */
    public function getUri();

    /**
     * @return SprayFire.Http.RequestHeaders
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return float
     */
    public function getVersion();

}