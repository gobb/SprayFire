<?php

/**
 * Exception thrown by SprayFire.Service.Container implementations.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Service\Exception;

/**
 * Should be thrown by implementations of SprayFire.Service.Container when a service
 * is attempted to be retrieved that was not properly added to the container.
 *
 * @package SprayFire
 * @subpackage Service.Exception
 */
class ServiceNotFound extends \Exception {}
