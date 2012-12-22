<?php

/**
 * Interface used to parse messages for a given SprayFire.Validation.Check.Check
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
interface MessageParser extends SFObject {

    /**
     * Should return a formatted string with the values in $tokenValues matching
     * the corresponding tokens in $message.
     *
     * @param string $message
     * @param array $tokenValues
     * @return string
     */
    public function parseMessage($message, array $tokenValues);

}