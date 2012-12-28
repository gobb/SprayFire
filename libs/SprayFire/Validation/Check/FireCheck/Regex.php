<?php

/**
 * An implementation of \SprayFire\Validation\Check\FireCheck\Check that will
 * match a value against a regular expression pattern.
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
class Regex extends Check {

    /**
     * The regular expression pattern that will be matched against the checked value
     *
     * @property string
     */
    protected $pattern = '';

    /**
     * Pass in the regular expression that you want to match against the checked
     * value.
     *
     * @param string $pattern
     */
    public function __construct($pattern) {
        $this->pattern = (string) $pattern;
    }

    /**
     * Check if a $value matches the regex pattern passed at construction.
     *
     * Possible error codes:
     * - ErrorCodes::NO_ERROR
     *
     * @param string $value
     * @return int
     */
    public function passesCheck($value) {
        $value = (string) $value;
        parent::passesCheck($value);
        if (\preg_match($this->pattern, $value)) {
            return ErrorCodes::NO_ERROR;
        }
    }

    /**
     * Return the name of the check that should be used when __toString() is called
     *
     * @return string
     */
    protected function getCheckName() {
        return 'Regex';
    }
}
