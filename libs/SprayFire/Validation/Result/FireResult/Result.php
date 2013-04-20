<?php

/**
 * Very simple implementation of SprayFire.Validation.Result.Result that simply
 * passes data injected at construction time to various getters.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\Result\FireResult;

use \SprayFire\Validation\Result as SFResult,
    \SprayFire\StdLib;

/**
 * @package SprayFire
 * @subpackage Validation.Result.FireResult
 */
class Result extends StdLib\CoreObject implements SFResult\Result {

    /**
     * @property string
     */
    protected $fieldName;

    /**
     * @property mixed
     */
    protected $fieldValue;

    /**
     * This array should have 2 keys 'log' and 'display' holding formatted messages
     *
     * @property array
     */
    protected $messages;

    /**
     * @property string
     */
    protected $checkName;

    /**
     * @property boolean
     */
    protected $passedCheck;

    /**
     * @param string $fieldName
     * @param mixed $fieldValue
     * @param string $checkName
     * @param boolean $passedCheck
     * @param array $messages
     */
    public function __construct($fieldName, $fieldValue, $checkName, $passedCheck, array $messages) {
        $this->fieldName = (string) $fieldName;
        $this->fieldValue = $fieldValue;
        $this->checkName = (string) $checkName;
        $this->passedCheck = (boolean) $passedCheck;
        $this->messages = $messages;
    }

    /**
     * @return string
     */
    public function getDisplayMessage() {
        return $this->messages['display'];
    }

    /**
     * @return string
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * @return mixed
     */
    public function getFieldValue() {
        return $this->fieldValue;
    }

    /**
     * @return string
     */
    public function getLogMessage() {
        return $this->messages['log'];
    }

    /**
     * @return boolean
     */
    public function passedCheck() {
        return $this->passedCheck;
    }

    /**
     * @return string
     */
    public function getCheckName() {
        return $this->checkName;
    }

}
