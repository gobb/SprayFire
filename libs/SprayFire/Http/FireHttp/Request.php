<?php

/**
 * Implementation of \SprayFire\Http\Request designed to work with an Apache server
 * or any server that populates the $_SERVER global with commonly expected values.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Http\FireHttp;

use \SprayFire\Http,
    \SprayFire\StdLib;

/**
 * This implementation is specifically designed to work with common indexes available
 * to the superglobal $_SERVER.
 *
 * This superglobal is known to be populated with the appropriate values on Apache
 * servers but has not been tested on other servers.
 *
 * @package SprayFire
 * @subpackage Http.Implementation
 */
class Request extends StdLib\CoreObject implements Http\Request {

    /**
     * @property \SprayFire\Http\Uri
     */
    protected $Uri;

    /**
     * @property \SprayFire\Http\RequestHeaders
     */
    protected $Headers;

    /**
     * @property string
     */
    protected $method;

    /**
     * @property string
     */
    protected $version;

    /**
     * @param \SprayFire\Http\Uri $Uri
     * @param \SprayFire\Http\RequestHeaders $Headers
     * @param array $_server
     */
    public function __construct(Http\Uri $Uri, Http\RequestHeaders $Headers, array $_server = null) {
        $this->Uri = $Uri;
        $this->Headers = $Headers;
        $_server = $_server ?: $_SERVER;
        $this->parseMethodAndVersion($_server);
    }

    /**
     * Will set the appropriate $version and $method based on the values from the
     * passed configuration, if the values could not be found then the method is
     * defaulted to GET and the HTTP version is set to a blank value.
     *
     * @param array $_server
     */
    protected function parseMethodAndVersion(array $_server) {
        $this->method = isset($_server['REQUEST_METHOD']) ? \strtoupper($_server['REQUEST_METHOD']) : Http\Request::METHOD_GET;
        $version = '1.0';
        if (isset($_server['SERVER_PROTOCOL'])) {
            $version = \preg_replace('/[^0-9\.]/', '', $_server['SERVER_PROTOCOL']);
        }
        $this->version = $version;
    }

    /**
     * @return \SprayFire\Http\RequestHeaders
     */
    public function getHeaders() {
        return $this->Headers;
    }

    /**
     * The string returned will always be in UPPER CASE letters
     *
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @return \SprayFire\Http\Uri
     */
    public function getUri() {
        return $this->Uri;
    }

    /**
     * This string will be returned as a string with a x.x decimal format.
     *
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

}
