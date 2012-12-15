<?php

/**
 * Default implementation of SprayFire.Validation.Check.Check
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

use \SprayFire\Validation\Check as SFValidationCheck,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * @package SprayFire
 * @subpackage`Validation.Check.FireCheck
 */
abstract class Check extends SFCoreObject implements SFValidationCheck\Check {

    /**
     * Error code used if the $errorCode passed to various functions are null
     */
    const DEFAULT_ERROR_CODE = 1;

    /**
     * Will format the appropriate messages to support tokens
     *
     * @property SprayFire.Validation.Check.MessageParser
     */
    protected $MessageParser;

    /**
     * Map of tokens => values that should be used when parsing log and display
     * messages.
     *
     * @property array
     */
    protected $tokenValues = array();

    /**
     * Map of errorCode => logMessage values.
     *
     * @property array
     */
    protected $logMessages = array();

    /**
     * Map of errorCode => displayMessage values
     *
     * @property array
     */
    protected $displayMessages = array();

    /**
     * @param SprayFire.Validation.Check.MessageParser $MessageParser
     */
    public function __construct(SFValidationCheck\MessageParser $MessageParser) {
        $this->MessageParser = $MessageParser;
    }

    /**
     * Only here to provide a convenient way to set token value parameter.
     *
     * DO NOT RELY ON THIS TO RETURN PROPER ERROR CODE!
     *
     * @param string $value
     * @return null
     */
    public function passesCheck($value) {
        $this->setTokenValue('value', $value);
    }

    /**
     * Will return a string parsed by the injected $this->MessageParser with the
     * values returned by $this->getTokenValues().
     *
     * @param integer $errorCode
     * @return string
     */
    protected function getLogMessage($errorCode) {
        $message = '';
        if (isset($this->logMessages[$errorCode])) {
            $message = $this->MessageParser->parseMessage($this->logMessages[$errorCode], (array) $this->getTokenValues());
        }
        return $message;
    }

    /**
     * Will return a string parsed by the injected $this->MessageParser with the
     * values returned by this->getTokenValues()
     *
     * @param integer $errorCode
     * @return string
     */
    protected function getDisplayMessage($errorCode) {
        $message = '';
        if (isset($this->displayMessages[$errorCode])) {
            $message = $this->MessageParser->parseMessage($this->displayMessages[$errorCode], (array) $this->getTokenValues());
        }
        return $message;
    }

    /**
     * Will return an array of 'log' and 'display' messages matching the passed
     * $errorCode.
     *
     * If no error code is passed the Check::DEFAULT_ERROR_CODE will be used.
     *
     * @param integer $errorCode
     * @return array
     */
    public function getMessages($errorCode = null) {
        $errorCode = $this->getErrorCode($errorCode);
        $log = $this->getLogMessage($errorCode);
        $display = $this->getDisplayMessage($errorCode);
        return \compact('log', 'display');
    }

    /**
     * @param string $message
     * @param integer $errorCode
     */
    public function setLogMessage($message, $errorCode = null) {
        $errorCode = $this->getErrorCode($errorCode);
        $this->logMessages[$errorCode] = (string) $message;
    }

    /**
     * @param string $message
     * @param integer $errorCode
     */
    public function setDisplayMessage($message, $errorCode = null) {
        $errorCode = $this->getErrorCode($errorCode);
        $this->displayMessages[$errorCode] = (string) $message;
    }

    /**
     * Should return an array of [token => value] used for log and display messages.
     *
     * @return array
     */
    abstract protected function getTokenValues();

    /**
     * Helper method that could be used to implement getTokenValues simply as
     * return $this->tokenValues and using this method to set all token values.
     *
     * @param string $token
     * @param string $value
     */
    protected function setTokenValue($token, $value) {
        $this->tokenValues[(string) $token] = (string) $value;
    }

    /**
     * Will return the passed $errorCode unless it is not set in which case the
     * default error code will be returned.
     *
     * @param integer $errorCode
     * @return integer
     */
    protected function getErrorCode($errorCode) {
        return isset($errorCode) ? (int) $errorCode : self::DEFAULT_ERROR_CODE;
    }

}