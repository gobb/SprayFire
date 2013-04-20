<?php

/**
 * Exception thrown if a Route pattern has already been added to the bag.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Routing\Exception;

use \RuntimeException;

/**
 * @package SprayFire
 * @subpackage Routing.Exception
 */
class DuplicateRouteAdded extends RuntimeException {

}
