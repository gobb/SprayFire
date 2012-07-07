<?php

/**
 * An interface representing the different parts of a Universe Resource Identifier
 * (URI) as dictated in http://tools.ietf.org/html/rfc3986.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http;

interface Uri {

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