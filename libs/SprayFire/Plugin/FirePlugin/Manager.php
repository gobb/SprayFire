<?php

/**
 * Framework provided implementation of \SprayFire\Plugin\Manager that will manage
 * the plugin registration process; check out the class level docs for more details
 * on what this will entail.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin\FirePlugin;

use \SprayFire\Plugin as SFPlugin,
    \SprayFire\StdLib as SFStdLib,
    \SprayFire\Plugin\Exception as SFPluginException,
    \ClassLoader\Loader as ClassLoader;

/**
 * This implementation is a very basic object that will allow simple management
 * of plugins allowing autoloading for any plugin to be easily setup using the
 * SprayFire provided autoloading solution and will run a plugin's bootstrap if
 * configured.
 *
 * At minimum the following should occur:
 * - Set up the plugin for autoloading
 * - Initialize the plugin, if configured to do so. By convention this means
 *   that we will look for a \PluginName\Bootstrap object, instantiate it and
 *   invoke it. Please check out \SprayFire\Plugin\FirePlugin\PluginInitializer
 *   for more information.
 *
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
     * Used to initialize and bootstrap the plugin if necessary.
     *
     * @property \SprayFire\Plugin\FirePlugin\PluginInitializer
     */
    protected $Initializer;

    /**
     * A collection of \SprayFire\Plugin\PluginSignature objects that have been
     * registered by this Manager.
     *
     * @property array
     */
    protected $registeredPlugins = [];

    /**
     * @param \SprayFire\Plugin\FirePlugin\PluginInitializer $Initializer
     * @param \ClassLoader\Loader $Loader
     */
    public function __construct(PluginInitializer $Initializer, ClassLoader $Loader) {
        $this->Initializer = $Initializer;
        $this->Loader = $Loader;
    }

    /**
     * Registering a plugin should involve any tasks the plugin needs to be setup
     * and start working.
     *
     * See class level documentation for more information on what occurs when you
     * register a plugin.
     *
     * @param \SprayFire\Plugin\PluginSignature $Signature
     * @return \SprayFire\Plugin\FirePlugin\Manager
     * @throws \InvalidArgumentException
     */
    public function registerPlugin(SFPlugin\PluginSignature $Signature) {
        $name = $Signature->getName();
        $this->Loader->registerNamespaceDirectory($name, $Signature->getDirectory());

        if ($Signature->initializePlugin()) {
            $this->Initializer->initializePlugin($name);
        }

        $this->registeredPlugins[] = $Signature;
        return $this;
    }

    /**
     * The $plugins parameter should be a structure containing \SprayFire\Plugin\PluginSignature[].
     *
     * See class level documentation for more information on what occurs when you
     * register a plugin.
     *
     * An exception should be thrown when an element in $plugins does not properly
     * implement the PluginSignature interface.
     *
     * @param array|\Traversable $plugins
     * @return \SprayFire\Plugin\FirePlugin\Manager
     * @throws \InvalidArgumentException
     */
    public function registerPlugins($plugins) {
        foreach($plugins as $Signature) {
            if (!($Signature instanceof SFPlugin\PluginSignature)) {
                $message = '%s expects collection of plugins to implement %s, %s given.';
                throw new SFPluginException\ElementNotPluginSignature(\sprintf($message, __CLASS__, '\\SprayFire\\Plugin\\PluginSignature', \get_class($Signature)));
            }
            $this->registerPlugin($Signature);
        }
        return $this;
    }

    /**
     * Will return a collection of \SprayFire\Plugin\PluginSignature implementations
     * that have been registered so far.
     *
     * @return array|\Traversable
     */
    public function getRegisteredPlugins() {
        return $this->registeredPlugins;
    }

}
