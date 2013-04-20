<?php

/**
 * Interface that says some message provides tokens that should be filled with
 * some value.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\Check;

use \SprayFire\Object;

/**
 * @package SprayFire
 * @subpackage Validation.Check
 */
interface MessageTokenizable extends Object {

    /**
     * A map of [token => value] that should be used for whatever message is
     * being represented by the implementing class.
     *
     * @return array
     */
    public function getTokenValues();

}
