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
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class LessThan extends ComparisonCheck {

    /**
     * Error code returned if the $value is equal to the info being compared against
     */
    const EQUAL_TO_ERROR = 2;

    /**
     * Error code returned if the $value is greater than the info being compared against
     */
    const GREATER_THAN_ERROR = 3;

    /**
     * @param integer $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value < $this->comparisonParameter) {
            return self::NO_ERROR;
        } elseif ($value == $this->comparisonParameter) {
            return self::EQUAL_TO_ERROR;
        }
        return self::GREATER_THAN_ERROR;
    }

}