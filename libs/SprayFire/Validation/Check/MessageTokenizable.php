<?php

/**
 * Interface that says some message provides tokens that should be filled with
 * some value.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Validation.Check
 */
interface MessageTokenizable extends SFObject {

    /**
     * A map of [token => value] that should be used for whatever message is
     * being represented by the implementing class.
     *
     * @return array
     */
    public function getTokenValues();

}