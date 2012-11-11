<?php

/**
 * An exception thrown if a SprayFire.Service.Container is attempting to create
 * a service using a factoryKey that has not been registered.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Service\Exception;

/**
 * @package SprayFire
 * @subpackage`Service.Exception
 */
class FactoryNotRegistered extends \InvalidArgumentException {

}