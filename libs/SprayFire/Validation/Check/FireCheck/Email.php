<?php

/**
 * Implementation to check that a value is a valid email format.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

/**
 * This implementation will use the filter_var functionality to ensure we are
 * checking values against the RFC for email address format.
 *
 * Tokens avilable
 * -----------------------------------------------------------------------------
 * - value => The value being checked for proper email format
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class Email extends Check {

    /**
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     *
     * @param string $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        if (\filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            return ErrorCodes::NO_ERROR;
        }
    }

}