<?php

/**
 * Base class for abstracting out the HTTP information pertinent to routing a
 * request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\FireHttp;

use \SprayFire\Http\Request as HttpRequest,
    \SprayFire\Http\Uri as HttpUri,
    \SprayFire\Http\RequestHeaders as HttpRequestHeaders,
    \SprayFire\CoreObject as CoreObject;


class Request extends CoreObject implements HttpRequest {

    /**
     * @property SprayFire.Http.Uri
     */
    protected $Uri;

    /**
     * @property SprayFire.Http.RequestHeaders
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
     * @param SprayFire.Http.Uri $Uri
     * @param SprayFire.HttpRequestHeaders $Headers
     * @param array $_server
     */
    public function __construct(HttpUri $Uri, HttpRequestHeaders $Headers, array $_server = null) {
        $this->Uri = $Uri;
        $this->Headers = $Headers;
        if (\is_null($_server)) {
            $_server = $_SERVER;
        }
        $this->parseMethodAndVersion($_server);
    }

    /**
     * @param array $_server
     */
    protected function parseMethodAndVersion(array $_server) {
        $this->method = isset($_server['REQUEST_METHOD']) ? \strtoupper($_server['REQUEST_METHOD']) : 'GET';
        $version = '';
        if (isset($_server['SERVER_PROTOCOL'])) {
            $version = \preg_replace('/[^0-9\.]/', '', $_server['SERVER_PROTOCOL']);
        }
        $this->version = $version;
    }

    /**
     *
     * @return SprayFire.Http.RequestHeaders
     */
    public function getHeaders() {
        return $this->Headers;
    }

    /**
     *
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @return SprayFire.Http.Uri
     */
    public function getUri() {
        return $this->Uri;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

}