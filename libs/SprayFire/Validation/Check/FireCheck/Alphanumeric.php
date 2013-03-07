<?php

/**
 * Implementation of \SprayFire\Validation\Check\FireCheck\Check that ensures a
 * string value is properly alphanumeric.
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
 * - value => The value checked against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class Alphanumeric extends Regex {

    /**
     * Passed to constructor parameter to cause spaces to be invalid in checked
     * values, this is set by default.
     */
    const DO_NOT_IGNORE_SPACES = false;

    /**
     * Passed to constructor parameter to cause spaces to not make checked
     * value invalid.
     */
    const IGNORE_SPACES = true;

    /**
     * @property string
     */
    protected $patternWithSpaces = '/[^A-Za-z0-9 ]/';

    /**
     * @property string
     */
    protected $patternWithoutSpaces = '/[^A-Za-z0-9]/';

    /**
     * @param boolean $ignoreSpaces
     */
    public function __construct($ignoreSpaces = self::DO_NOT_IGNORE_SPACES) {
        $pattern = $ignoreSpaces ? $this->patternWithSpaces : $this->patternWithoutSpaces;
        parent::__construct($pattern);
    }

    /**
     * Checks if a $value is alphanumeric, you can pass an optional parameter at
     * construction to cause the check to ignore spaces.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::NOT_ALPHANUMERIC_ERROR
     *
     * @param string $value
     * @return int
     */
    public function passesCheck($value) {
        // We are checking to see if the regex did not match because we are passing
        // a negated regex, that will only match if invalid characters are found
        if (parent::passesCheck($value) === ErrorCodes::REGEX_NOT_MATCHED_ERROR) {
            return ErrorCodes::NO_ERROR;
        }
        return ErrorCodes::NOT_ALPHANUMERIC_ERROR;
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
