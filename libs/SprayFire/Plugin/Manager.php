<?php

/**
 * Interface responsible for managing various plugins that are enabled for a
 * given request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Plugin
 */
interface Manager extends SFObject {

    /**
     * Registering a plugin should involve any tasks the plugin needs to be setup
     * and start working; the return value is up to the implementation and can
     * be whatever is needed or desired.
     *
     * At minimum the following should occur:
     * - Set up the plugin for autoloading
     * - Add any callbacks associated with the plugin to the Mediator
     *
     * @param \SprayFire\Plugin\PluginSignature $PluginSignature
     * @return mixed
     */
    public function registerPlugin(PluginSignature $PluginSignature);

    /**
     * The $plugins parameter should be a structure containing \SprayFire\Plugin\PluginSignature[].
     *
     * An exception should be thrown when an element in $plugins does not properly
     * implement the PluginSignature interface.
     *
     * @param array|Traversable $plugins
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function registerPlugins($plugins);

    /**
     * Will return a collection of \SprayFire\Plugin\PluginSignature implementations
     * that have been registered so far.
     *
     * @return array|Traversable
     */
    public function getRegisteredPlugins();

}
