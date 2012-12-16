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
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class GreaterThan extends ComparisonCheck {

    /**
     * Error code returned if the $value is equal to the comparison value
     */
    const EQUAL_TO_ERROR = 2;

    /**
     * Error code returned if the $value is less than the comparison value
     */
    const LESS_THAN_ERROR = 3;

    /**
     * Checks to see if the integer $value is greaterThan the comparison value.
     *
     * @param integer $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value > $this->comparisonParameter) {
            return self::NO_ERROR;
        } elseif ($value == $this->comparisonParameter) {
            return self::EQUAL_TO_ERROR;
        }
        return self::LESS_THAN_ERROR;
    }

}