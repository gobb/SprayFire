<?php

/**
 * Interface representing data in the HTTP request headers.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Http;

use SprayFire\Object as SFObject;

/**
 * Implementations of this interface should be returned from
 * SprayFire.Http.Request::getHeaders.
 *
 * @package SprayFire
 * @subpackage Http
 */
interface RequestHeaders extends SFObject {

    /**
     * @return string
     */
    public function getHost();

    /**
     * @return string
     */
    public function getConnectionType();

    /**
     * @return string
     */
    public function getCacheControl();

    /**
     * @return string
     */
    public function getUserAgent();

    /**
     * @return string
     */
    public function getAcceptType();

    /**
     * @return string
     */
    public function getAcceptEncoding();

    /**
     * @return string
     */
    public function getAcceptLanguage();

    /**
     * @return string
     */
    public function getAcceptCharset();

    /**
     * @return string
     */
    public function getReferer();

    /**
     * @return boolean
     */
    public function isAjaxRequest();



}
