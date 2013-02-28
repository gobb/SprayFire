<?php

/**
 * Exception thrown if a return type restriction or a null object passed to a
 * Factory could not be loaded.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Factory\Exception;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * @package SprayFire
 * @subpackage Factory.Exception
 */
class TypeNotFound extends InvalidArgumentException {

}
