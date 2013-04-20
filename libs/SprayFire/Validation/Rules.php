<?php

/**
 * Interface to determine how a set of data should be validated
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation;

use \SprayFire\Validation\Check,
    \SprayFire\Object;

/**
 * @package SprayFire
 * @subpackage Validation
 */
interface Rules extends Object {

    /**
     * Adds a $Check to the end of the chain for a given $field, if $breakOnFailure
     * is true if the given $Check does not pass the $field value the chain will
     * stop with this check.
     *
     * $Check should be ran in the order they are added to the Rules
     *
     * @param string $field
     * @param \SprayFire\Validation\Check\Check $Check
     * @param boolean $breakOnFailure
     */
    public function addCheck($field, Check\Check $Check, $breakOnFailure = false);

    /**
     * Return a Traversable structure that communicates what order of $Checks to
     * run and whether a $Check should break on failure.
     *
     * @param string $field
     * @return \Traversable
     */
    public function getChecks($field);

}
