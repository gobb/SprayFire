<?php

/**
 * Implementation of \SprayFire\Validation\Check\FireCheck\Check that ensures an integer
 * or a float are within a range, optionally being inclusive of the range.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Validation\Check\FireCheck;

/**
 * Tokens available
 * --------------------------------------------------------------
 * - value => The value being checked
 * - min => The minimum number of the range
 * - max => The maximum number of the range
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class Range extends Check {

    /**
     * Message token that is made available for the minimum limit of the range.
     */
    const MINIMUM_TOKEN = 'min';

    /**
     * Message token that is made available for the maximum limit of the range.
     */
    const MAXIMUM_TOKEN = 'max';

    /**
     * Constant to be passed to third parameter for an exclusive check, by default
     * this is the value if no third parameter is passed
     */
    const EXCLUSIVE_CHECK = 'exclusive';

    /**
     * Constant to be passed to third parameter for an inclusive check.
     */
    const INCLUSIVE_CHECK = 'inclusive';

    /**
     * The minimum limit of the range that a $value must be greater than, or
     * with an exclusive check greater or equal than.
     *
     * @property integer
     */
    protected $min = 0;

    /**
     * The maximum limit of the range that a value must be less than, or with an
     * exclusive check less or equal than.
     *
     * @property integer
     */
    protected $max = \PHP_INT_MAX;

    /**
     * Used to determine whether the checking algorithm should use an exclusive or
     * inclusive check.
     *
     * @property string
     */
    protected $checkType = self::EXCLUSIVE_CHECK;

    /**
     * @param integer|float $min
     * @param integer|float $max
     */
    public function __construct($min, $max, $checkType = self::EXCLUSIVE_CHECK) {
        $this->setMin($min);
        $this->setMax($max);
        $this->setCheckType($checkType);
    }

    /**
     * Will set the $min class property and make available the token 'min' with
     * the passed value.
     *
     * @param integer|float $min
     */
    protected function setMin($min) {
        $this->min = $min;
        $this->setTokenValue(self::MINIMUM_TOKEN, $min);
    }

    /**
     * Will set the $max class property and make available the token 'max' with
     * the passed value.
     *
     * @param integer|float $max
     */
    protected function setMax($max) {
        $this->max = $max;
        $this->setTokenValue(self::MAXIMUM_TOKEN, $max);
    }

    /**
     * Ensures that a valid check type is set and will trigger an error if an
     * invalid type is attempted to be set.
     *
     * @param string $type
     */
    protected function setCheckType($type) {
        $validTypes = array(
            self::INCLUSIVE_CHECK,
            self::EXCLUSIVE_CHECK
        );
        $validType = $type;
        if (!\in_array($type, $validTypes)) {
            $message = 'Invalid check type passed to ' . __CLASS__ . '. Exclusive check used by default.';
            \trigger_error($message, \E_USER_NOTICE);
            $validType = self::EXCLUSIVE_CHECK;
        }
        $this->checkType = $validType;
    }

    /**
     * Ensures that the passed $value is within the range injected at construction
     * time.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::MINIMUM_LIMIT_ERROR
     * - ErrorCodes::MAXIMUM_LIMIT_ERROR
     *
     * @param integer|float $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if (self::EXCLUSIVE_CHECK === $this->checkType) {
            $code = $this->doExclusiveCheck($value);
        } else {
            $code = $this->doInclusiveCheck($value);
        }
        return $code;
    }

    /**
     * @param integer|float $value
     * @return integer
     */
    protected function doExclusiveCheck($value) {
        if ($this->min <= $value && $this->max >= $value) {
            return ErrorCodes::NO_ERROR;
        } else {
            if ($this->min > $value) {
                return ErrorCodes::MINIMUM_LIMIT_ERROR;
            } else {
                return ErrorCodes::MAXIMUM_LIMIT_ERROR;
            }
        }
    }

    /**
     * @param integer|float $value
     * @return integer
     */
    protected function doInclusiveCheck($value) {
        if ($this->min < $value && $this->max > $value) {
            return ErrorCodes::NO_ERROR;
        } else {
            if ($this->min >= $value) {
                return ErrorCodes::MINIMUM_LIMIT_ERROR;
            } else {
                return ErrorCodes::MAXIMUM_LIMIT_ERROR;
            }
        }
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'Range';
    }

}
