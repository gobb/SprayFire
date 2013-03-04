<?php

/**
 * Framework provided implementation of \SprayFire\Plugin\Manager that will setup
 * autoloading for a plugin and ensure the callbacks associated to that plugin are
 * added to the Mediator.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin\FirePlugin;

use \SprayFire\Plugin as SFPlugin,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\StdLib as SFStdLib,
    \ClassLoader\Loader as ClassLoader;

/**
 * @package SprayFire
 * @subpackage Plugin.FirePlugin
 */
class Manager extends SFStdLib\CoreObject implements SFPlugin\Manager {

    /**
     * Holds a ClassLoader\Loader implementation to allow the Manager the ability
     * to easily setup autoloading for a given plugin.
     *
     * @property \ClassLoader\Loader
     */
    protected $Loader;

    /**
     * A collection of \SprayFire\Plugin\PluginSignature objects that have been
     * registered by this Manager.
     *
     * @property array
     */
    protected $registeredPlugins = [];

    /**
     * @param \ClassLoader\Loader $Loader
     * @param \SprayFire\Mediator\Mediator $Mediator
     */
    public function __construct(ClassLoader $Loader, SFMediator\Mediator $Mediator) {
        $this->Loader = $Loader;
    }

    /**
     * Registering a plugin should involve any tasks the plugin needs to be setup
     * and start working.
     *
     * At minimum the following should occur:
     * - Set up the plugin for autoloading
     * - Add any callbacks associated with the plugin to the Mediator
     *
     * @param \SprayFire\Plugin\PluginSignature $PluginSignature
     * @return \SprayFire\Plugin\FirePlugin\Manager
     */
    public function registerPlugin(SFPlugin\PluginSignature $Signature) {
        $this->Loader->registerNamespaceDirectory($Signature->getName(), $Signature->getDirectory());

        $this->registeredPlugins[] = $Signature;
        return $this;
    }

    /**
     * The $plugins parameter should be a structure containing \SprayFire\Plugin\PluginSignature[].
     *
     * An exception should be thrown when an element in $plugins does not properly
     * implement the PluginSignature interface.
     *
     * @param array|Traversable $plugins
     * @return \SprayFire\Plugin\FirePlugin\Manager
     * @throws \InvalidArgumentException
     */
    public function registerPlugins($plugins) {

    }

    /**
     * Will return a collection of \SprayFire\Plugin\PluginSignature implementations
     * that have been registered so far.
     *
     * @return array|Traversable
     */
    public function getRegisteredPlugins() {
        return $this->registeredPlugins;
    }

}
