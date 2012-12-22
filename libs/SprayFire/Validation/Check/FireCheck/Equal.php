<?php

/**
 * Implementation of SprayFire.Validation.Check.FireCheck.Check to compare the
 * equality of values and parameters.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

/**
 * This implementation will compare on a strict '===' check.
 *
 * Tokens available
 * -----------------------------------------------------------------------------
 * - value => The value being checked
 * - comparison => The value being compared against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class Equal extends ComparisonCheck {

    /**
     * Passes if $value is strictly equal '===' to value passed at construction
     *
     * Possible error codes returned:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::NOT_EQUAL_TO_ERROR
     *
     * @param mixed $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value === $this->comparisonParameter) {
            return ErrorCodes::NO_ERROR;
        }
        return ErrorCodes::NOT_EQUAL_TO_ERROR;
    }

    protected function getCheckName() {
        return 'Equal';
    }

}