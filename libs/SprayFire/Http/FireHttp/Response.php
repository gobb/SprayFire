<?php

/**
 * Concrete implementation of \SprayFire\Http\Response provided by default with
 * the framework.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Http\FireHttp;

use \SprayFire\Http as SFHttp,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFire
 * @subpackage Http
 */
class Response extends SFStdLib\CoreObject implements SFHttp\Response {

    /**
     * A constant used with the setCode() method to determine that we should set
     * the default reason for that code.
     */
    const DO_SET_REASON = true;

    /**
     * A constant used with the setCode() method to determine that we should NOT
     * set the default reason for that code.
     */
    const DO_NOT_SET_REASON = false;

    /**
     * @property integer
     */
    protected $statusCode;

    /**
     * @property string
     */
    protected $statusReason;

    /**
     * @property string
     */
    protected $body;

    /**
     * @property array
     */
    protected $headers = [];

    /**
     * This is an associative array holding [HTTP status code => default status reason].
     *
     * If you have the setCode() method also set the status reason this is the
     * map the reasons will be pulled from.
     *
     * @property array
     */
    protected $codeReasonMap = [
        self::STATUS_100 => 'Continue',
        self::STATUS_101 => 'Switching Protocols',
        self::STATUS_102 => 'Processing',           // WebDAV
        self::STATUS_200 => 'OK',
        self::STATUS_201 => 'Created',
        self::STATUS_202 => 'Accepted',
        self::STATUS_203 => 'Non-Authoritative Information',
        self::STATUS_204 => 'No Content',
        self::STATUS_205 => 'Reset Content',
        self::STATUS_206 => 'Partial Content',
        self::STATUS_207 => 'Multi-Status',         // WebDAV
        self::STATUS_208 => 'Already Reported',     // WebDav
        self::STATUS_226 => 'IM Used',
        self::STATUS_300 => 'Multiple Choices',
        self::STATUS_301 => 'Moved Permanently',
        self::STATUS_302 => 'Found',
        self::STATUS_303 => 'See Other',
        self::STATUS_304 => 'Not Modified',
        self::STATUS_305 => 'Use Proxy',
        self::STATUS_306 => 'Unused',
        self::STATUS_307 => 'Temporary Redirect',
        self::STATUS_308 => 'Permanent Redirect',
        self::STATUS_400 => 'Bad Request',
        self::STATUS_401 => 'Unauthorized',
        self::STATUS_402 => 'Payment Required',
        self::STATUS_403 => 'Forbidden',
        self::STATUS_404 => 'Not Found',
        self::STATUS_405 => 'Method Not Allowed',
        self::STATUS_406 => 'Not Acceptable',
        self::STATUS_407 => 'Proxy Authentication Required',
        self::STATUS_408 => 'Request Timeout',
        self::STATUS_409 => 'Conflict',
        self::STATUS_410 => 'Gone',
        self::STATUS_411 => 'Length Required',
        self::STATUS_412 => 'Precondition Failed',
        self::STATUS_413 => 'Request Entity Too Large',
        self::STATUS_414 => 'Request-URI Too Long',
        self::STATUS_415 => 'Unsupported Media Type',
        self::STATUS_416 => 'Requested Range Not Satisfiable',
        self::STATUS_417 => 'Expectation Failed',
        self::STATUS_418 => 'I\'m a teapot',
        self::STATUS_420 => 'Enhance Your Calm',        // Twitter
        self::STATUS_422 => 'Unprocessable Entity',     // WebDAV
        self::STATUS_423 => 'Locked',                   // WebDAV
        self::STATUS_424 => 'Failed Dependency',        // WebDAV
        self::STATUS_425 => 'Reserved for WebDAV',      // WebDAV
        self::STATUS_426 => 'Upgrade Required',
        self::STATUS_428 => 'Precondition Required',
        self::STATUS_429 => 'Too Many Requests',
        self::STATUS_431 => 'Request Header Fields Too Large',
        self::STATUS_444 => 'No Response',              // Nginx
        self::STATUS_449 => 'Retry With',               // Microsoft
        self::STATUS_450 => 'Blocked by Windows Parental Controls',     // Microsoft
        self::STATUS_499 => 'Client Closed Request',    // Nginx
        self::STATUS_500 => 'Internal Server Error',
        self::STATUS_501 => 'Not Implemented',
        self::STATUS_502 => 'Bad Gateway',
        self::STATUS_503 => 'Service Unavailable',
        self::STATUS_504 => 'Gateway Timeout',
        self::STATUS_505 => 'HTTP Version Not Supported',
        self::STATUS_506 => 'Variable Also Negotiates',
        self::STATUS_507 => 'Insufficient Storage',      // WebDAV
        self::STATUS_508 => 'Loop Detected',             // WebDAV
        self::STATUS_509 => 'Bandwidth Limit Exceeded',  // Apache
        self::STATUS_510 => 'Not Extended',
        self::STATUS_511 => 'Network Authentication Required',
        self::STATUS_598 => 'Network Read Timeout Error',
        self::STATUS_599 => 'Network Connect Timeout Error'
    ];

    /**
     * Sets the default code, reason and headers that should be populated.
     *
     * If the $statusReason is a non-string value then it will be populated with
     * the appropriate response matching the $statusCode passed or it will be
     * filled with an empty string if the $statusCode is an unknown code.
     *
     * @param integer $statusCode
     * @param string|null $statusReason
     * @param array $headers
     */
    public function __construct($statusCode = self::STATUS_200, $statusReason = null, array $headers = []) {
        $this->setStatusCode($statusCode);
        if (!\is_string($statusReason)) {
            $statusReason = $this->getReasonForCode($statusCode);
        }
        $this->setStatusReason($statusReason);
        foreach ($headers as $headerKey => $headerVal) {
            $this->addHeader($headerKey, $headerVal);
        }
    }

    /**
     * Return the integer HTTP code that will be sent with this response.
     *
     * @return integer
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * Set the integer HTTP code that will be sent with this response; it is
     * highly recommended that you take advantage of the constants made available
     * by this interface.
     *
     * This method will also, optionally, set the default reason for the $code
     * passed. If false or the constant Response::DO_NOT_SET_REASON is passed to
     * second parameter the reason will NOT be set to its default value and will
     * retain whatever value has been placed in it previously.
     *
     * @param integer $code
     * @param bool $setReason
     * @return \SprayFire\Http\Response
     */
    public function setStatusCode($code, $setReason = self::DO_SET_REASON) {
        $this->statusCode = (int) $code;

        if ($setReason) {
            $reason = $this->getReasonForCode($this->statusCode);
            $this->setStatusReason($reason);
        }

        return $this;
    }

    /**
     * Return the human readable text representation for why the status is what it is.
     *
     * @return string
     */
    public function getStatusReason() {
        return $this->statusReason;
    }

    /**
     * Set the human readable text representation of the status.
     *
     * @param string $reason
     * @return \SprayFire\Http\Response
     */
    public function setStatusReason($reason) {
        $this->statusReason = $reason;
        return $this;
    }

    /**
     * HTTP response bodies are ultimately always a string, regardless of how they
     * might be interpreted by the client when rendering the output.
     *
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Should set the body of the response as a string ready to be echod back to the
     * client.
     *
     * @param string $body
     * @return \SprayFire\Http\Response
     */
    public function setBody($body) {
        $this->body = (string) $body;
        return $this;
    }

    /**
     * Will add a header key/value pair to be sent with the response; the $headerKey
     * should be on the left side of the ':' and the $headerValue on the right.
     *
     * If a $headerKey is added twice the key is overridden.
     *
     * No colon is needed when passing the values to the method.
     *
     * @param string $headerKey
     * @param string $headerValue
     * @return \SprayFire\Http\Response
     */
    public function addHeader($headerKey, $headerValue) {
        $this->headers[$headerKey] = $headerValue;
        return $this;
    }

    /**
     * Return an array of header key/value pairs that will be sent with this
     * response.
     *
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Return the string header value set against $key or null if no $key has been
     * set.
     *
     * @param string $key
     * @return string|null
     */
    public function getHeader($key) {
        return $this->headers[$key];
    }

    /**
     * Return true or false for whether a value has been set against the given
     * header $key
     *
     * @param string $key
     * @return boolean
     */
    public function hasHeader($key) {
        // TODO: Implement hasHeader() method.
    }

    /**
     * Will remove a header key/value pair represented by $headerKey; note that if
     * this method is called after send() it will ultimately have no effect as the
     * header will have already been sent.
     *
     * @param string $headerKey
     * @return \SprayFire\Http\Response
     */
    public function removeHeader($headerKey) {
        // TODO: Implement removeHeader() method.
    }

    /**
     * Should remove all the headers previously added leaving an empty container.
     *
     * @return \SprayFire\Http\Response
     */
    public function removeAllHeaders() {
        // TODO: Implement removeAllHeaders() method.
    }

    /**
     * Should prepare and send all added headers and echo out the string contents
     * of the body; this method should NOT end processing of the script.
     *
     * @return boolean
     */
    public function send() {
        // TODO: Implement send() method.
    }

    /**
     * Returns true if the HTTP status code set is explicitly set to 200
     *
     * @return boolean
     */
    public function isOk() {
        return $this->statusCode === self::STATUS_200;
    }

    /**
     * Returns true if the HTTP status code is something that is not an error; note
     * that this may not necessarily mean a 200 response.
     *
     * @return boolean
     */
    public function isSuccess() {
        return (200 <= $this->statusCode && 300 > $this->statusCode);
    }

    /**
     * Returns true if the HTTP status code set would cause a redirect being sent
     * to the client.
     *
     * @return boolean
     */
    public function isRedirect() {
        return (300 <= $this->statusCode && 400 > $this->statusCode);
    }

    /**
     * Returns true if the HTTP status code indicates that an error was encountered
     * processing the request as a result of some client action.
     *
     * @return boolean
     */
    public function isClientError() {
        return ($this->statusCode < 500 && $this->statusCode >= 400);
    }

    /**
     * Returns true if the server crapped the bed during processing.
     *
     * @return boolean
     */
    public function isServerError() {
        return (500 <= $this->statusCode && 600 > $this->statusCode);
    }

    /**
     * Returns true if the HTTP status code indicates that the resource requested
     * could not be found.
     *
     * @return boolean
     */
    public function isNotFound() {
        return $this->statusCode === self::STATUS_404;
    }

    /**
     * Returns the default reason for the given status $code or a blank string
     * if it could not be found.
     *
     * @param integer $code
     * @return string
     */
    protected function getReasonForCode($code) {
        $statusReason = '';
        if (isset($this->codeReasonMap[$code])) {
            $statusReason = $this->codeReasonMap[$code];
        }

        return $statusReason;
    }

}
