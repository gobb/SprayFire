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
class Equal extends Check {

    /**
     * Constant used with Check::setParameter() to determine what the check should
     * compare the passed value against.
     */
    const COMPARISON_PARAMETER = 'comparison';

    /**
     * Constant used in messages as a token for the value being compared against
     */
    const COMPARISON_TOKEN = 'comparison';

    /**
     * A set of default parameters that will be used if none were set explicitly
     *
     * @property array
     */
    protected $defaultParameters = array(
        self::COMPARISON_PARAMETER => ''
    );

    /**
     * Passes if $value is strictly equal '===' to value set to COMPARISON_PARAMETER
     *
     * @param string $value
     * @return integer
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        $comparison = $this->getParameter(self::COMPARISON_PARAMETER);
        $this->setTokenValue(self::COMPARISON_TOKEN, $comparison);
        if ($value !== $comparison) {
            return self::DEFAULT_ERROR_CODE;
        }
        return self::NO_ERROR;
    }

    /**
     * @return array
     */
    protected function getTokenValues() {
        return $this->tokenValues;
    }
}