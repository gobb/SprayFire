<?php

/**
 * An exception is thrown if an event is attempted to be registered more than one
 * time.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Mediator\Exception;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * @package SprayFire
 * @subpackage Mediator.Exception
 */
class DuplicateRegisteredEvent extends InvalidArgumentException {

}
