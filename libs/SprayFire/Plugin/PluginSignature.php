<?php

/**
 * Interface that represents the information needed to load a plugin and manage
 * how that plugin interacts with the framework.
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
interface PluginSignature extends SFObject {

    /**
     * Return the name of the plugin, this should correspond to the top level
     * namespace for your plugin.
     *
     * @return string
     */
    public function getName();

    /**
     * Return the absolute path that your plugin's classes should be loaded from.
     *
     * Do not include the name of the plugin itself in the directory. For example,
     * if you have SprayFire installed in /root and your plugin is in the libs
     * folder of your install then this function would return '/root/libs'.
     *
     * @return string
     */
    public function getDirectory();

    /**
     * Return a collection of \SprayFire\Mediator\Callback objects that will be
     * registered against the Mediator; the data structure returned should be
     * Traversable and every element in that structure should implement
     * \SprayFire\Mediator\Callback.
     *
     * @return \SprayFire\Mediator\Callback[]
     */
    public function getCallbacks();

}
