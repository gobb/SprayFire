<?php

/**
 * Implementation of SprayFire.Http.RequestHeaders designed to work with an
 * Apache server.
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
 * Will determine the appropriate HTTP request headers based on common indices
 * found in the superglobal $_SERVER.
 *
 * This implementation is known to work with Apache servers but may not work with
 * others if that server does not properly populate the $_SERVER superglobal with
 * the appropriate indices.
 *
 *
 * @package SprayFire
 * @subpackage Http.Implementation
 */
class RequestHeaders extends StdLib\CoreObject implements Http\RequestHeaders {

    /**
     * @property string
     */
    protected $acceptCharset = '';

    /**
     * @property string
     */
    protected $acceptEncoding = '';

    /**
     * @property string
     */
    protected $acceptLanguage = '';

    /**
     * @property string
     */
    protected $acceptType = '';

    /**
     * @property string
     */
    protected $cacheControl = '';

    /**
     * @property string
     */
    protected $connectionType = '';

    /**
     * @property string
     */
    protected $host = '';

    /**
     * @property string
     */
    protected $referer = '';

    /**
     * @property string
     */
    protected $userAgent = '';

    /**
     * @property string
     */
    protected $xRequestedWith = '';

    /**
     * @property array
     */
    protected $serverToHeadersMap = [
        'HTTP_HOST' => 'host',
        'HTTP_CONNECTION' => 'connectionType',
        'HTTP_CACHE_CONTROL' => 'cacheControl',
        'HTTP_USER_AGENT' => 'userAgent',
        'HTTP_ACCEPT' => 'acceptType',
        'HTTP_ACCEPT_CHARSET' => 'acceptCharset',
        'HTTP_ACCEPT_ENCODING' => 'acceptEncoding',
        'HTTP_ACCEPT_LANGUAGE' => 'acceptLanguage',
        'HTTP_REFERER' => 'referer',
        'HTTP_X_REQUESTED_WITH' => 'xRequestedWith'
    ];


    /**
     * The $_server parameter is optional, if it is not provided the superglobal
     * $_SERVER will be used to parse the various pieces of information.
     *
     * @param array $_server
     */
    public function __construct(array $_server = null) {
        if (\is_null($_server)) {
            $_server = $_SERVER;
        }
        foreach ($this->serverToHeadersMap as $index => $property) {
            if (\array_key_exists($index, $_server) && isset($_server[$index])) {
                $this->$property = $_server[$index];
            }
        }
    }

    /**
     * @return string
     */
    public function getAcceptCharset() {
        return $this->acceptCharset;
    }

    /**
     * @return string
     */
    public function getAcceptEncoding() {
        return $this->acceptEncoding;
    }

    /**
     * @return string
     */
    public function getAcceptLanguage() {
        return $this->acceptLanguage;
    }

    /**
     * @return string
     */
    public function getAcceptType() {
        return $this->acceptType;
    }

    /**
     * @return string
     */
    public function getCacheControl() {
        return $this->cacheControl;
    }

    /**
     * @return string
     */
    public function getConnectionType() {
        return $this->connectionType;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getReferer() {
        return $this->referer;
    }

    /**
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * @return boolean
     */
    public function isAjaxRequest() {
        return (bool) $this->xRequestedWith;
    }

}
