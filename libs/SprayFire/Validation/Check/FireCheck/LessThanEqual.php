<?php

/**
 * Implementation of \SprayFire\Validation\Check\FireCheck\ComparisonCheck that
 * will ensure a value is less than or equal to an injected comparison.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Validation\Check\FireCheck;

/**
 * Tokens available to implementations
 * --------------------------------------------------------------
 * - value => The value being checked
 * - comparison => The value being checked against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class LessThanEqual extends ComparisonCheck {

    /**
     * Ensures that an integer or float is less than or equal to the constructor
     * injected $comparisonParameter.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::GREATER_THAN_ERROR
     *
     * @param integer|float $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value <= $this->comparisonParameter) {
            return ErrorCodes::NO_ERROR;
        }
        return ErrorCodes::GREATER_THAN_ERROR;
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'LessThanEqual';
    }

}
