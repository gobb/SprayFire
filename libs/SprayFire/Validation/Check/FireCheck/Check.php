<?php

/**
 * Default implementation of \SprayFire\Validation\Check\Check
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
 * Tokens available to this implementation
 * -----------------------------------------------------------------------------
 * - value => The value being checked
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
abstract class Check extends SFCoreObject implements SFValidationCheck\Check {

    /**
     * In logging and display messages this token will be replaced with the value
     * being checked against.
     */
    const VALUE_TOKEN = 'value';

    /**
     * Map of tokens => values that should be used when parsing log and display
     * messages.
     *
     * @property array
     */
    protected $tokenValues = [];

    /**
     * Map of errorCode => logMessage values.
     *
     * @property array
     */
    protected $logMessages = [];

    /**
     * Map of errorCode => displayMessage values
     *
     * @property array
     */
    protected $displayMessages = [];

    /**
     * Only here to provide a convenient way to set token value parameter.
     *
     * DO NOT RELY ON THIS TO RETURN PROPER ERROR CODE!
     *
     * @param string $value
     * @return null
     */
    public function passesCheck($value) {
        $this->setTokenValue(self::VALUE_TOKEN, $value);
    }

    /**
     * Will return a string parsed by the injected $this->MessageParser with the
     * values returned by $this->getTokenValues().
     *
     * @param integer $errorCode
     * @return string
     */
    protected function getLogMessage($errorCode) {
        return (isset($this->logMessages[$errorCode])) ? $this->logMessages[$errorCode] : '';
    }

    /**
     * Will return a string parsed by the injected $this->MessageParser with the
     * values returned by this->getTokenValues()
     *
     * @param integer $errorCode
     * @return string
     */
    protected function getDisplayMessage($errorCode) {
        return (isset($this->displayMessages[$errorCode])) ? $this->displayMessages[$errorCode] : '';
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
    public function getMessages($errorCode) {
        $errorCode = (int) $errorCode;
        $log = $this->getLogMessage($errorCode);
        $display = $this->getDisplayMessage($errorCode);
        return \compact('log', 'display');
    }

    /**
     * @param string $message
     * @param integer $errorCode
     */
    public function setLogMessage($message, $errorCode) {
        $this->logMessages[(int) $errorCode] = (string) $message;
    }

    /**
     * @param string $message
     * @param integer $errorCode
     */
    public function setDisplayMessage($message, $errorCode) {
        $this->displayMessages[(int) $errorCode] = (string) $message;
    }

    /**
     * Message token values that should be used to format the appropriate message.
     *
     * @return array
     */
    public function getTokenValues() {
        return $this->tokenValues;
    }

    /**
     * Returns the name of the check, as returned from Check::getCheckName
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->getCheckName();
    }

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
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    abstract protected function getCheckName();

}
