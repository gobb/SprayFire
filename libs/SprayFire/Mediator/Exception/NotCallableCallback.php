<?php

/**
 * An exception thrown if a not callable parameter is passed to a Callback object
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Mediator\Exception;

use \InvalidArgumentException as InvalidArgumentException;

/**
 *
 *
 * @package SprayFire
 * @subpackage Mediator.Exception
 */
class NotCallableCallback extends InvalidArgumentException {

}
