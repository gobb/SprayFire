<?php

/**
 * Implementation to ensure that an integer is less than a checked $value
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
 * - value => The value checked against
 * - comparison => The value being compared against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class LessThan extends ComparisonCheck {

    /**
     * Ensure that the $value is less than the $comparisonParameter
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::EQUAL_TO_ERROR
     * - ErrorCodes::GREATER_THAN_ERROR
     *
     * @param integer $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value < $this->comparisonParameter) {
            return ErrorCodes::NO_ERROR;
        } elseif ($value == $this->comparisonParameter) {
            return ErrorCodes::EQUAL_TO_ERROR;
        }
        return ErrorCodes::GREATER_THAN_ERROR;
    }

    protected function getCheckName() {
        return 'LessThan';
    }

}