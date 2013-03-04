<?php

/**
 * Exception to be thrown if a collection of PluginSignatures is registered but
 * an element in that collection does not properly implement the PluginSignature
 * interface.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin\Exception;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * @package SprayFire
 * @subpackage Plugin.Exception
 */
class ElementNotPluginSignature extends InvalidArgumentException {

}
