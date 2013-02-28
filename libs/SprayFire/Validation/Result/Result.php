<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
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
    public function passedCheck();

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @return mixed
     */
    public function getFieldValue();

    /**
     * @return string
     */
    public function getLogMessage();

    /**
     * @return string
     */
    public function getDisplayMessage();

    /**
     * @return string
     */
    public function getCheckName();

}
