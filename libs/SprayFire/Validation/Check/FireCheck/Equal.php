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

use \SprayFire\Validation\Check as SFValidationCheck;

/**
 * This implementation will compare on a strict '===' check.
 *
 * Constants
 * -------------------------------------------------------------
 * - COMPARISON_PARAMETER => parameter name for value to compare against
 * - COMPARISON_TOKEN => token name for comparison value in messages
 *
 * Tokens available:
 * - value => The value being checked
 * - comparison => The value being checked against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class Equal extends ComparisonCheck {

    /**
     * Passes if $value is strictly equal '===' to value set to COMPARISON_PARAMETER
     *
     * @param string $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if ($value === $this->comparisonParameter) {
            return self::NO_ERROR;
        }
        return self::DEFAULT_ERROR_CODE;
    }

}