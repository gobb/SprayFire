<?php

/**
 * Exception to be thrown if a plugin has been configured to be initialized but
 * the bootstrap for that plugin could not be located.
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
class PluginBootstrapNotFound extends RuntimeException {

}
