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
     * @property string
     */
    protected $alphabeticRegex = '/[^A-Za-z]/';

    public function __construct() {
        parent::__construct($this->alphabeticRegex);
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
        if ($matchCode === ErrorCodes::REGEX_NOT_MATCHED) {
            return ErrorCodes::NO_ERROR;
        }
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
