<?php

/**
 * Exception thrown if an immutable Session\Storage object is attempted to be
 * written to.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Session\Exception;

use \RuntimeException as RuntimeException;

/**
 * @package SprayFire
 * @subpackage Session.Exception
 */
class WritingToImmutableStorage extends RuntimeException {

}
