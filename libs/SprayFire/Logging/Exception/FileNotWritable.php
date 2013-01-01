<?php

/**
 * Exception thrown if a file passed to a log could not be properly written to.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Logging\Exception;

use \InvalidArgumentException as InvalidArgumentException;

/**
 *
 *
 * @package SprayFire
 * @subpackage Logging.Exception
 */
class FileNotWritable extends InvalidArgumentException {

}
