<?php

/**
 * Check to see if a value is greater than or equal to the comparison parameter.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

/**
 * Tokens available
 * -----------------------------------------------------------------------------
 * - value => The value being checked
 * - comparison => The value being compared against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class GreaterThanEqual extends ComparisonCheck {

    /**
     * Checks to see if the $value is greater than or equal to the $comparisonParameter
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::LESS_THAN_ERROR
     *
     * @param integer $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value >= $this->comparisonParameter) {
            return ErrorCodes::NO_ERROR;
        }
        return ErrorCodes::LESS_THAN_ERROR;
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'GreaterThanEqual';
    }


}
