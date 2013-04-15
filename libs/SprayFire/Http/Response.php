<?php

/**
 * An interface that represents an HTTP response that will be sent to the client
 * upon a processing of an HTTP request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Http;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Http
 */
interface Response extends SFObject {

    const STATUS_100 = 100;
    const STATUS_101 = 101;
    const STATUS_102 = 102;
    const STATUS_200 = 200;
    const STATUS_201 = 201;
    const STATUS_202 = 202;
    const STATUS_203 = 203;
    const STATUS_204 = 204;
    const STATUS_205 = 205;
    const STATUS_206 = 206;
    const STATUS_207 = 207;
    const STATUS_208 = 208;
    const STATUS_226 = 226;
    const STATUS_300 = 300;
    const STATUS_301 = 301;
    const STATUS_302 = 302;
    const STATUS_303 = 303;
    const STATUS_304 = 304;
    const STATUS_305 = 305;
    const STATUS_306 = 306;
    const STATUS_307 = 307;
    const STATUS_308 = 308;
    const STATUS_400 = 400;
    const STATUS_401 = 401;
    const STATUS_402 = 402;
    const STATUS_403 = 403;
    const STATUS_404 = 404;
    const STATUS_405 = 405;
    const STATUS_406 = 406;
    const STATUS_407 = 407;
    const STATUS_408 = 408;
    const STATUS_409 = 409;
    const STATUS_410 = 410;
    const STATUS_411 = 411;
    const STATUS_412 = 412;
    const STATUS_413 = 413;
    const STATUS_414 = 414;
    const STATUS_415 = 415;
    const STATUS_416 = 416;
    const STATUS_417 = 417;
    const STATUS_418 = 418;
    const STATUS_420 = 420;
    const STATUS_422 = 422;
    const STATUS_423 = 423;
    const STATUS_424 = 424;
    const STATUS_425 = 425;
    const STATUS_426 = 426;
    const STATUS_428 = 428;
    const STATUS_429 = 429;
    const STATUS_431 = 431;
    const STATUS_444 = 444;
    const STATUS_449 = 449;
    const STATUS_450 = 450;
    const STATUS_499 = 499;
    const STATUS_500 = 500;
    const STATUS_501 = 501;
    const STATUS_502 = 502;
    const STATUS_503 = 503;
    const STATUS_504 = 504;
    const STATUS_505 = 505;
    const STATUS_506 = 506;
    const STATUS_507 = 507;
    const STATUS_508 = 508;
    const STATUS_509 = 509;
    const STATUS_510 = 510;
    const STATUS_511 = 511;
    const STATUS_598 = 598;
    const STATUS_599 = 599;

    const HTML_RESPONSE_TYPE = 'text/html';
    const XHTML_RESPONSE_TYPE = 'application/xhtml+xml';
    const PLAIN_TEXT_RESPONSE_TYPE = 'text/plain';
    const JSON_RESPONSE_TYPE = 'application/json';
    const XML_RESPONSE_TYPE = 'application/xml';

    /**
     * Return the integer HTTP code that will be sent with this response.
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Set the integer HTTP code that will be sent with this response; it is
     * highly recommended that you take advantage of the constants made available
     * by this interface.
     *
     * @param integer $code
     * @return \SprayFire\Http\Response
     */
    public function setStatusCode($code);

    /**
     * Return the human readable text representation for why the status is what it is.
     *
     * @return string
     */
    public function getStatusReason();

    /**
     * Set the human readable text representation of the status.
     *
     * @param string $reason
     * @return \SprayFire\Http\Response
     */
    public function setStatusReason($reason);

    /**
     * HTTP response bodies are ultimately always a string, regardless of how they
     * might be interpreted by the client when rendering the output.
     *
     * @return string
     */
    public function getBody();

    /**
     * Should set the body of the response as a string ready to be echod back to the
     * client.
     *
     * @param string $body
     * @return \SprayFire\Http\Response
     */
    public function setBody($body);

    /**
     * Will add a header key/value pair to be sent with the response; the $headerKey
     * should be on the left side of the ':' and the $headerValue on the right.
     *
     * If a $headerKey is added twice it is up to the implementation to determine
     * best course of action. However, it is suggested that the default behavior
     * be that the $headerKey is overridden.
     *
     * No colon is needed when passing the values to the method.
     *
     * @param string $headerKey
     * @param string $headerValue
     * @return \SprayFire\Http\Response
     */
    public function addHeader($headerKey, $headerValue);

    /**
     * Return the string header value set against $key or null if no $key has been
     * set.
     *
     * @param string $key
     * @return string|null
     */
    public function getHeader($key);

    /**
     * Return an array of header key/value pairs that will be sent with this
     * response.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Return true or false for whether a value has been set against the given
     * header $key
     *
     * @param string $key
     * @return boolean
     */
    public function hasHeader($key);

    /**
     * Will remove a header key/value pair represented by $headerKey; note that if
     * this method is called after send() it will ultimately have no effect as the
     * header will have already been sent.
     *
     * @param string $headerKey
     * @return \SprayFire\Http\Response
     */
    public function removeHeader($headerKey);

    /**
     * Should remove all the headers previously added leaving an empty container.
     *
     * @return \SprayFire\Http\Response
     */
    public function removeAllHeaders();

    /**
     * Should prepare and send all added headers and echo out the string contents
     * of the body; this method should NOT end processing of the script.
     *
     * @return boolean
     */
    public function send();

    /**
     * Returns true if the HTTP status code set is explicitly set to 200
     *
     * @return boolean
     */
    public function isOk();

    /**
     * Returns true if the HTTP status code is something that is not an error; note
     * that this may not necessarily mean a 200 response.
     *
     * @return boolean
     */
    public function isSuccess();

    /**
     * Returns true if the HTTP status code set would cause a redirect being sent
     * to the client.
     *
     * @return boolean
     */
    public function isRedirect();

    /**
     * Returns true if the HTTP status code indicates that an error was encountered
     * processing the request as a result of some client action.
     *
     * @return boolean
     */
    public function isClientError();

    /**
     * Returns true if the server crapped the bed during processing.
     *
     * @return boolean
     */
    public function isServerError();

    /**
     * Returns true if the HTTP status code indicates that the resource requested
     * could not be found.
     *
     * @return boolean
     */
    public function isNotFound();



}
