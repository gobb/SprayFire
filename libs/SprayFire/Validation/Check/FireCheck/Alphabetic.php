<?php

/**
 * A check that determines if a string value consists of only alphabetic characters,
 * a boolean flag may be passed to ignore spaces in the value.
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
    protected $alphabeticRegex = '/[^A-Za-z]/';

    /**
     * @property string
     */
    protected $alphabeticWithSpacesRegex = '/[^A-Za-z ]/';

    /**
     * @param boolean $ignoreSpaces
     */
    public function __construct($ignoreSpaces = self::DO_NOT_IGNORE_SPACES) {
        $this->ignoreSpaces = (boolean) $ignoreSpaces;
        if ($this->ignoreSpaces) {
            $pattern = $this->alphabeticWithSpacesRegex;
        } else {
            $pattern = $this->alphabeticRegex;
        }
        parent::__construct($pattern);

    }

    /**
     * Determines if the passed value is an alphabetic string, an optional
     * parameter can be passed at construction time to cause this check to ignore
     * all spaces.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     *
     * @param string $value
     * @return int
     */
    public function passesCheck($value) {
        $matchCode = parent::passesCheck($value);
        // We are checking to see if the regex did not match because we are passing
        // a negated regex, that will only match if invalid characters are found
        if ($matchCode === ErrorCodes::REGEX_NOT_MATCHED) {
            return ErrorCodes::NO_ERROR;
        }
        return ErrorCodes::NOT_ALPHABETIC;
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
