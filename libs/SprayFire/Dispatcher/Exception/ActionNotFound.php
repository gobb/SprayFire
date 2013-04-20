<?php

/**
 * Exception thrown if the controller for a route does not have the appropriate
 * action requested.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Dispatcher\Exception;

use \RuntimeException;

/**
 * Exception thrown if the controller for a route does not have the appropriate
 * action requested.
 *
 * @package SprayFire
 * @subpackage Dispatcher.Exception
 */
class ActionNotFound extends RuntimeException {

}
