<?php

/**
 * Exception thrown if an event is attempted to be triggered or have callbacks added
 * to that have not been registered with the \SprayFire\Mediator\FireMediator\EventRegistry
 * implementation.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Mediator\Exception;

use \RuntimeException;

/**
 *
 *
 * @package SprayFire
 * @subpackage Mediator.Exception
 */
class EventNotRegistered extends RuntimeException {

}
