<?php

/**
 * Thrown if a method implemented by an interface should not be callable for a
 * specific implementation.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Exception;

use \RuntimeException as RuntimeException;

/**
 * Thrown if a method implemented by an interface should not be callable for a
 * specific implementation.
 *
 * @package SprayFire
 * @subpackage Exception
 */
class UnsupportedOperationException extends RuntimeException {

}
