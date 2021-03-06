<?php

/**
 * Class acting as an enum of possible error codes for the various Checks
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

/**
 * This implementation is here because error codes should be unique and you can
 * have multiple checks returning similar errors.
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
abstract class ErrorCodes {

    /**
     * Code returned if no error for the check
     */
    const NO_ERROR = 0;

    /**
     * Code returned if the error is because the value is equal to the comparison
     */
    const EQUAL_TO_ERROR = 2;

    /**
     * Code returned if the error is because the value is not equal to the comparison
     */
    const NOT_EQUAL_TO_ERROR = 3;

    /**
     * Code returned if the error is because the value is greater than the comparison
     */
    const GREATER_THAN_ERROR = 4;

    /**
     * Code returned if the error is because the value is less than the comparison
     */
    const LESS_THAN_ERROR = 5;

    /**
     * Code returned if the error is because the value is an invalid email address
     */
    const INVALID_EMAIL_ERROR = 6;

    /**
     * Code returned if a regular expression pattern is not matched by a checked value
     */
    const REGEX_NOT_MATCHED_ERROR = 7;

    /**
     * Code returned if a string is not properly recognized as being only alphabetic
     * characters A-Z and a-z.
     */
    const NOT_ALPHABETIC_ERROR = 8;

    /**
     * Code returned if a string is not properly recognized as being only alphanumeric
     * characters A-Z, a-z and 0-9.
     */
    const NOT_ALPHANUMERIC_ERROR = 9;

    /**
     * Code returned if a number in a range breaks the minimum limit.
     */
    const MINIMUM_LIMIT_ERROR = 10;

    /**
     * Code returned if a number in a range breaks the maximum limit.
     */
    const MAXIMUM_LIMIT_ERROR = 11;

}
