<?php

/**
 * A check that determines if a string value consists of only alphabetic characters,
 * a boolean flag may be passed to ignore spaces in the value.
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
class Alphabetic extends Regex {

    /**
     * Value passed to construct parameter to ignore spaces in values checked
     */
    const IGNORE_SPACES = true;

    /**
     * Value passed to construct parameter if spaces are not to be ignored and
     * would result in an invalid check on a value if it does have spaces in it
     */
    const DO_NOT_IGNORE_SPACES = false;

    /**
     * @property boolean
     */
    protected $ignoreSpaces;

    /**
     * @property string
     */
    protected $patternWithoutSpaces = '/[^A-Za-z]/';

    /**
     * @property string
     */
    protected $patternWithSpaces = '/[^A-Za-z ]/';

    /**
     * @param boolean $ignoreSpaces
     */
    public function __construct($ignoreSpaces = self::DO_NOT_IGNORE_SPACES) {
        $this->ignoreSpaces = (boolean) $ignoreSpaces;
        $pattern = $ignoreSpaces ? $this->patternWithSpaces : $this->patternWithoutSpaces;
        parent::__construct($pattern);
    }

    /**
     * Determines if the passed value is an alphabetic string, an optional
     * parameter can be passed at construction time to cause this check to ignore
     * all spaces.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     * - ErrorCodes::NOT_ALPHABETIC
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
        return ErrorCodes::NOT_ALPHABETIC_ERROR;
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'Alphabetic';
    }

}
