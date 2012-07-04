<?php

/**
 * Base class abstracting away typical headers sent in a HTTP request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http;

use \SprayFire\Http\RequestHeaders as RequestHeaders,
    \SprayFire\CoreObject as CoreObject;

class StandardRequestHeaders extends CoreObject implements RequestHeaders {

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
    protected $serverToHeadersMap = array(
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
    );


    /**
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