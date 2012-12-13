<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Result;

use \SprayFire\Object as SFObject;

/**
 *
 *
 * @package SprayFire
 * @subpackage Validation.Result
 */
interface Result extends SFObject {

    /**
     * @return boolean
     */
    public function isValid();

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @return mixed
     */
    public function getFieldValue();

    /**
     * @return array
     */
    public function getErrorMessages();

    /**
     * @return string
     */
    public function getRuleName();

}