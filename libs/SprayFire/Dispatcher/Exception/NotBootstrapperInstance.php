<?php

/**
 * Exception thrown if the application's bootstrap does not appropriately implement
 * \SprayFire\Bootstrap\Bootstrapper
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Dispatcher\Exception;

use \RuntimeException as RuntimeException;

/**
 * Exception thrown if the application's bootstrap does not appropriately implement
 * \SprayFire\Bootstrap\Bootstrapper
 *
 * @package SprayFire
 * @subpackage Dispatcher.Exception
 */
class NotBootstrapperInstance extends RuntimeException {

}
