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

use \SprayFire\Object;

/**
 * While any number of things could happen when a plugin is registered this interface
 * is meant to serve as a contract for the bare minimum of what should happen;
 * since that is impossible to do in code this interface somewhat breaks normal
 * rules in that it is dictating to an extent what the implementation should be
 * like, ultimately though each implementation of this interface should meet the
 * minimum requirements.
 *
 * At minimum the following should occur:
 * - Set up the plugin for autoloading
 * - Run an optional bootstrap for the plugin, by convention located at \Plugin\Bootstrap
 *
 * @package SprayFire
 * @subpackage Plugin.Interface
 */
interface Manager extends Object {

    /**
     * Registering a plugin should involve any tasks the plugin needs to be setup
     * and start working; the return value is up to the implementation and can
     * be whatever is needed or desired.
     *
     * Please read the detailed interface level documentation for more info on
     * what should be taken care of when a plugin is registered.
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
     * @param array|\Traversable $plugins
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function registerPlugins($plugins);

    /**
     * Will return a collection of \SprayFire\Plugin\PluginSignature implementations
     * that have been registered so far.
     *
     * @return array|\Traversable
     */
    public function getRegisteredPlugins();

}
