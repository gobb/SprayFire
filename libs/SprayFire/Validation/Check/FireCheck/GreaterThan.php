<?php

/**
 * A check to ensure that an integer value is greater than a set parameter.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

/**
 * Tokens available
 * -----------------------------------------------------------------------------
 * - value => The value being checked
 * - comparison => The value being checked against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class GreaterThan extends ComparisonCheck {

    /**
     * Checks to see if the integer $value is greaterThan the comparison value.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::EQUAL_TO_ERROR
     * - ErrorCodes::LESS_THAN_ERROR
     *
     * @param integer $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value > $this->comparisonParameter) {
            return ErrorCodes::NO_ERROR;
        } elseif ($value == $this->comparisonParameter) {
            return ErrorCodes::EQUAL_TO_ERROR;
        }
        return ErrorCodes::LESS_THAN_ERROR;
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'GreaterThan';
    }

}
