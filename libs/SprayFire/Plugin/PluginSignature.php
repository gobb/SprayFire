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
     * Constant used to determine that a plugin SHOULD be initialized; that is a
     * bootstrap should be ran for that plugin.
     */
    const DO_INITIALIZE = true;

    /**
     * Constant used to determine that a plugin SHOULD NOT be initialized.
     */
    const DO_NOT_INITIALIZE = false;

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
     * Return true or false if the plugin has a bootstrap that should be ran
     * when the plugin is registered.
     *
     * By convention we typically expect this to mean a \PluginName\Bootstrap
     * object implements \SprayFire\Bootstrap\Bootstrapper and exists in the
     * appropriate directory.
     *
     * @return boolean
     */
    public function initializePlugin();

}
