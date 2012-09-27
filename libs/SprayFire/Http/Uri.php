<?php

/**
 * Interface representing an HTTP URI as dictated in http://tools.ietf.org/html/rfc3986.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http;

use \SprayFire\Object as SFObject;

interface Uri extends SFObject {

    /**
     * Will return the HTTP protocol for the given request
     *
     * @return string
     */
    public function getScheme();

    /**
     * Will return the HTTP host and port used for the given request.
     *
     * @return string
     */
    public function getAuthority();

    /**
     * Returns the port used for the request, a convenience method to easily
     * gather the port without having to parse the authority.
     *
     * @return int
     */
    public function getPort();

    /**
     * Returns any path associated with the URI
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns any query string associated with the URI
     *
     * @return string
     */
    public function getQuery();

}