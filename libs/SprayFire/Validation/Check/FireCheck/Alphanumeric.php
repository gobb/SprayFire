<?php

/**
 * Implementation of \SprayFire\Validation\Check\FireCheck\Check that ensures a
 * string value is properly alphanumeric.
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
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class Alphanumeric extends Regex {

    /**
     * @property string
     */
    protected $patternWithoutSpaces = '/[^A-Za-z0-9]/';

    public function __construct() {
        parent::__construct($this->patternWithoutSpaces);
    }

    /**
     * Checks if a $value is alphanumeric, you can pass an optional parameter at
     * construction to cause the check to ignore spaces.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     *
     * @param string $value
     * @return int
     */
    public function passesCheck($value) {
        // We are checking to see if the regex did not match because we are passing
        // a negated regex, that will only match if invalid characters are found
        if (parent::passesCheck($value) === ErrorCodes::REGEX_NOT_MATCHED) {
            return ErrorCodes::NO_ERROR;
        }
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'Alphanumeric';
    }
}
