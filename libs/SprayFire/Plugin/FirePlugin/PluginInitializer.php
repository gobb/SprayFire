<?php

/**
 * Non-interface backed implementation that handles the plug initialization process
 * for the Plugin\Manager.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */

namespace SprayFire\Plugin\FirePlugin;

use \SprayFire\Service,
    \SprayFire\Bootstrap,
    \SprayFire\StdLib,
    \SprayFire\Plugin,
    \ClassLoader\Loader;

/**
 * During framework initialization, after the Request has been routed, this class
 * will ensure that your app's bootstrap has been instantiated and invoked so that
 * you may run whatever startup scripts your app needs.
 *
 * @package SprayFire
 * @subpackage Plugin.Implementation
 */
class PluginInitializer extends StdLib\CoreObject {

    /**
     * Is here to provide the bootstrap process for the application a way to setup
     * autoloading for application specific third party libraries.
     *
     * Also here to ensure the application we parse from the SprayFire.Http.Routing.RoutedRequest
     * gets autoloading setup properly.
     *
     * @property \ClassLoader\Loader
     */
    protected $ClassLoader;

    /**
     * Is here to provide the bootstrap process for the application the service
     * container so that the appropriate application specific services may be added.
     *
     * @property \SprayFire\Service\Container
     */
    protected $Container;

    /**
     * @param \SprayFire\Service\Container $Container
     * @param \ClassLoader\Loader $ClassLoader
     */
    public function __construct(Service\Container $Container, Loader $ClassLoader) {
        $this->Container = $Container;
        $this->ClassLoader = $ClassLoader;
    }

    /**
     * Based on the $appNamespace determine if there is a \<AppName>\Bootstrap
     * class that properly implements \SprayFire\Bootstrap\Bootstrapper and, if
     * so, will instantiate and invoke the runBootstrap() object for the application.
     *
     * It is assumed that your application bootstraps are expecting a \SprayFire\Service\Container
     * and a \ClassLoader\Loader are injected at construction time.
     *
     * @param string $appNamespace
     * @return void
     * @throws \SprayFire\Plugin\Exception\PluginBootstrapNotFound
     * @throws \SprayFire\Plugin\Exception\PluginBootstrapWrongType
     */
    public function initializePlugin($appNamespace) {
        $bootstrapName = '\\' . $appNamespace . '\\Bootstrap';
        if (!\class_exists($bootstrapName)) {
            $message = 'The bootstrap for %s could not be found.  Please ensure you have created a %s object and autoloading has been setup for %s';
            throw new Plugin\Exception\PluginBootstrapNotFound(\sprintf($message, $appNamespace, $bootstrapName, $appNamespace));
        }

        /** @var \SprayFire\Bootstrap\Bootstrapper $Bootstrap */
        $Bootstrap = new $bootstrapName($this->Container, $this->ClassLoader);

        if (($Bootstrap instanceof Bootstrap\Bootstrapper) === false) {
            $message = '%s does not properly implement the %s interface';
            throw new Plugin\Exception\PluginBootstrapWrongType(\sprintf($message, $bootstrapName, '\\SprayFire\\Bootstrap\\Bootstrapper'));
        }

        $Bootstrap->runBootstrap();
    }

}
