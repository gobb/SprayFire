<?php

/**
 * Interface to allow a set of data to have its field value's validated against
 * a SprayFire.Validation.Rules implementation.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Validation
 */
interface Validator extends SFObject {

    /**
     * The $data parameter should be of format ['field' => 'value'], each field
     * in the data set will be checked against the $Rules passed.
     *
     * A SprayFire.Validation.Result.Set should always be returned regardless
     * of whether all fields passed the rules or not.
     *
     * @param array $data
     * @param \SprayFire\Validation\Rules $Rules
     * @return \SprayFire\Validation\Result\Set
     */
    public function validate(array $data, Rules $Rules);

}
