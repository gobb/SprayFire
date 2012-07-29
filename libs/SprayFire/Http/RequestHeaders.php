<?php

/**
 * Interface representing data sent by the request headers.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http;

use SprayFire\Object as Object;

interface RequestHeaders extends Object {

    /**
     *
     * @return string
     */
    public function getHost();

    /**
     *
     * @return string
     */
    public function getConnectionType();

    /**
     *
     * @return string
     */
    public function getCacheControl();

    /**
     *
     * @return string
     */
    public function getUserAgent();

    /**
     *
     * @return string
     */
    public function getAcceptType();

    /**
     *
     * @return string
     */
    public function getAcceptEncoding();

    /**
     *
     * @return string
     */
    public function getAcceptLanguage();

    /**
     *
     * @return string
     */
    public function getAcceptCharset();

    /**
     *
     * @return string
     */
    public function getReferer();

    /**
     *
     * @return boolean
     */
    public function isAjaxRequest();



}