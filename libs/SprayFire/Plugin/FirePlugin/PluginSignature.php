<?php

/**
 * Framework provided implementation of \SprayFire\Plugin\PluginSignature that
 * allows you to easily pass in a name, directory and callable function returning
 * a collection of Callbacks to satisfy the requirements of the interface.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin\FirePlugin;

use \SprayFire\Plugin as SFPlugin,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFire
 * @subpackage Plugin.FirePlugin
 */
class PluginSignature extends SFStdLib\CoreObject implements SFPlugin\PluginSignature {

    /**
     * The name and top level namespace of the plugin this signature represents
     *
     * @property string
     */
    protected $name;

    /**
     * The absolute path to the directory holding classes in the top level namespace
     * represented by $name.
     *
     * @property string
     */
    protected $dir;

    /**
     * A callable function that can be invoked to return a Traversable collection
     * of \SprayFire\Mediator\Callback objects.
     *
     * @property callable
     */
    protected $callbacks;

    /**
     * A flag used to determine if the plugin should be initialized.
     *
     * @property boolean
     */
    protected $initialize;

    /**
     * @param string $name
     * @param string $dir
     * @param string $callbacks
     * @param boolean $initialize
     */
    public function __construct($name, $dir, $initialize = true) {
        $this->name = (string) $name;
        $this->dir = (string) $dir;
        $this->initialize = (boolean) $initialize;
    }

    /**
     * Return the name of the plugin, this should correspond to the top level
     * namespace for your plugin.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Return the absolute path that your plugin's classes should be loaded from.
     *
     * Do not include the name of the plugin itself in the directory. For example,
     * if you have SprayFire installed in /root and your plugin is in the libs
     * folder of your install then this function would return '/root/libs'.
     *
     * @return string
     */
    public function getDirectory() {
        return $this->dir;
    }

    /**
     * Determines whether the plugin should be initialized or not.
     *
     * @return boolean
     */
    public function initializePlugin() {
        return $this->initialize;
    }

}
