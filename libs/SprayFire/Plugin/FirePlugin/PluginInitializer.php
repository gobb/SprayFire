<?php

/**
 * Implementation of SprayFire.Dispatcher.AppInitializer provided with the default
 * SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */

namespace SprayFire\Plugin\FirePlugin;

use \SprayFire\Service as SFService,
    \SprayFire\Bootstrap as SFBootstrap,
    \SprayFire\StdLib as SFStdLib,
    \SprayFire\Plugin\Exception as SFPluginException,
    \ClassLoader\Loader as ClassLoader;

/**
 * During framework initialization, after the Request has been routed, this class
 * will ensure that your app's bootstrap has been instantiated and invoked so that
 * you may run whatever startup scripts your app needs.
 *
 * @package SprayFire
 * @subpackage Dispatcher.FireDispatcher
 */
class PluginInitializer extends SFStdLib\CoreObject {

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
    public function __construct(SFService\Container $Container, ClassLoader $ClassLoader) {
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
            throw new SFPluginException\PluginBootstrapNotFound(\sprintf($message, $appNamespace, $bootstrapName, $appNamespace));
        }

        /** @var \SprayFire\Bootstrap\Bootstrapper $Bootstrap */
        $Bootstrap = new $bootstrapName($this->Container, $this->ClassLoader);

        if (($Bootstrap instanceof SFBootstrap\Bootstrapper) === false) {
            $message = '%s does not properly implement the %s interface';
            throw new SFPluginException\PluginBootstrapWrongType(\sprintf($message, $bootstrapName, '\\SprayFire\\Bootstrap\\Bootstrapper'));
        }

        $Bootstrap->runBootstrap();
    }

}
