<?php

/**
 * Implementation of SprayFire.Dispatcher.AppInitializer provided with the default
 * SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Dispatcher\FireDispatcher;

use \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\Service as SFService,
    \SprayFire\FileSys as SFFileSys,
    \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Bootstrap as SFBootstrap,
    \SprayFire\Exception as SFException,
    \SprayFire\CoreObject as SFCoreObject,
    \ClassLoader\Loader as ClassLoader;

/**
 * During framework initialization, after the Request has been routed, this class
 * will ensure that your app's bootstrap has been instantiated and invoked so that
 * you may run whatever startup scripts your app needs.
 *
 * @package SprayFire
 * @subpackage Dispatcher.FireDispatcher
 */
class AppInitializer extends SFCoreObject implements SFDispatcher\AppInitializer {

    /**
     * Is here to provide the bootstrap process for the application a way to setup
     * autoloading for application specific third party libraries.
     *
     * Also here to ensure the application we parse from the SprayFire.Http.Routing.RoutedRequest
     * gets autoloading setup properly.
     *
     * @property ClassLoader.Loader
     */
    protected $ClassLoader;

    /**
     * Is here to provide the bootstrap process for the application the service
     * container so that the appropriate application specific services may be added.
     *
     * @property SprayFire.Service.Container
     */
    protected $Container;

    /**
     * Is here to provide the directory that application specific classes should
     * be autoloaded from.
     *
     * @property SprayFire.FileSys.PathGenerator
     */
    protected $Paths;

    /**
     * @param \SprayFire\Service\Container $Container
     * @param \SprayFire\FileSys\PathGenerator $Paths
     * @param \ClassLoader\Loader $ClassLoader
     */
    public function __construct(SFService\Container $Container, SFFileSys\PathGenerator $Paths, ClassLoader $ClassLoader) {
        $this->Container = $Container;
        $this->Paths = $Paths;
        $this->ClassLoader = $ClassLoader;
    }

    /**
     * Based on the application namespace from the $RoutedRequest will setup the
     * appropriate autoloading and determine if there is a \<AppName>\Bootstrap
     * class that properly implements \SprayFire\Bootstrap\Bootstrapper and, if
     * so, will instantiate and invoke the runBootstrap() object for the application.
     *
     * It is assumed that your application bootstraps are expecting a \SprayFire\Service\Container
     * and a \ClassLoader\Loader are injected at construction time.
     *
     * @param \SprayFire\Http\Routing\RoutedRequest $RoutedRequest
     * @return void
     * @throws \SprayFire\Dispatcher\Exception\BootstrapNotFound
     * @throws \SprayFire\Dispatcher\Exception\NotBootstrapperInstance
     */
    public function initializeApp(SFRouting\RoutedRequest $RoutedRequest) {
        $appNamespace = $RoutedRequest->getAppNamespace();

        // @codeCoverageIgnoreStart
        // No reliable way to test
        if (\strtolower($appNamespace) === 'sprayfire') {
            return;
        }
        // @codeCoverageIgnoreEnd

        $this->ClassLoader->registerNamespaceDirectory($appNamespace, $this->Paths->getAppPath());
        $bootstrapName = '\\' . $appNamespace . '\\Bootstrap';
        if (!\class_exists($bootstrapName)) {
            $message = 'The application bootstrap for the RoutedRequest could not be found.  Please ensure you have created a \\' . $appNamespace . '\\Bootstrap object.';
            throw new SFDispatcher\Exception\BootstrapNotFound($message);
        }
        $Bootstrap = new $bootstrapName($this->Container, $this->ClassLoader);
        if (($Bootstrap instanceof SFBootstrap\Bootstrapper) === false) {
            $message = 'The application bootstrap, ' . $bootstrapName . ', for the RoutedRequest does not implement the appropriate interface, \\SprayFire\\Bootstrap\\Bootstrapper';
            throw new SFDispatcher\Exception\NotBootstrapperInstance($message);
        }
        $Bootstrap->runBootstrap();
    }

}
