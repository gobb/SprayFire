<?php

/**
 * Implementation of SprayFire.Validation.Check.FireCheck.Check that is intended
 * to provide generic functionality for checks that are simple binary comparisons
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

/**
 * Tokens available to implementations
 * --------------------------------------------------------------
 * - value => The value being checked
 * - comparison => The value being checked against
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
abstract class ComparisonCheck extends Check {

    /**
     * Constant used in messages as a token for the value being compared against
     */
    const COMPARISON_TOKEN = 'comparison';

    /**
     * The data that a $value should be compared against.
     *
     * @protected mixed
     */
    protected $comparisonParameter;

    /**
     * @param mixed $comparisonParameter
     */
    public function __construct($comparisonParameter) {
        $this->comparisonParameter = $comparisonParameter;
    }

    /**
     * Will set the $comparisonParameter property and will set the value of that
     * comparison parameter to be an available token value.
     *
     * DO NOT RELY ON THE RETURN OF THIS FUNCTION TO SUPPLY AN ERROR CODE!
     *
     * @param mixed $value
     * @return void
     */
    public function passesCheck($value) {
        parent::passesCheck($value);
        $this->setTokenValue(self::COMPARISON_TOKEN, $this->comparisonParameter);
    }

}
