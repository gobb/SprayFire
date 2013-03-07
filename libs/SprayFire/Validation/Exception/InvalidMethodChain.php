<?php

/**
 * Exception thrown if any Fluent API utilized by SprayFire.Validation implementations
 * has an invalid series of methods called.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\Exception;

use \RuntimeException as RuntimeException;

/**
 * @package SprayFire
 * @subpackage Validation.Exception
 */
class InvalidMethodChain extends RuntimeException {

}
