<?php

/**
 * Exception to be thrown if the plugin's bootstrap is not an appropriate type;
 * by convention SprayFire expect's the type to implement \SprayFire\Boootstrap\Bootstrapper
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin\Exception;

use \RuntimeException as RuntimeException;

/**
 * @package SprayFire
 * @subpackage Plugin.Exception
 */
class PluginBootstrapWrongType extends RuntimeException {

}
