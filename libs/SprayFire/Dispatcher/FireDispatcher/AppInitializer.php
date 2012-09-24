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

use \SprayFire\Http\Routing\RoutedRequest as RoutedRequest,
    \SprayFire\Service\Container as ServiceContainer,
    \SprayFire\FileSys\PathGenerator as Paths,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\Exception\ResourceNotFound as ResourceNotFoundException,
    \ClassLoader\Loader as ClassLoader;

class AppInitializer extends CoreObject implements \SprayFire\Dispatcher\AppInitializer {

    /**
     * Is here to provide the bootstrap process for the application a way to setup
     * autoloading for tapplication specific third party libraries.
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
     * @param SprayFire.Service.Container $Container
     * @param ClassLoader.Loader $ClassLoader
     * @param SprayFire.FileSys.PathGenerator $Paths
     */
    public function __construct(ServiceContainer $Container, ClassLoader $ClassLoader, Paths $Paths) {
        $this->Container = $Container;
        $this->ClassLoader = $ClassLoader;
        $this->Paths = $Paths;
    }

    /**
     * Based on the top level namespace from the controller of the $RoutedRequest
     * will setup the appropriate autoloading and determine if there is a
     * <AppName>.Bootstrap class that properly implements SprayFire.Bootstrap.Bootstrapper
     * and, if so, will instantiate and invoke the runBootstrap() object for the
     * application.
     *
     * It is assumed that your application bootstraps are expecting a SprayFire.Service.Container
     * and a ClassLoader.Loader are injected at construction time.
     *
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
     * @return void
     * @throws SprayFire.Exception.ResourceNotFound
     */
    public function initializeApp(RoutedRequest $RoutedRequest) {
        $appNamespace = $RoutedRequest->getAppNamespace();
        if (\strtolower($appNamespace) === 'sprayfire') {
            return;
        }
        $this->ClassLoader->registerNamespaceDirectory($appNamespace, $this->Paths->getAppPath());
        $bootstrapName = '\\' . $appNamespace . '\\Bootstrap';
        if (!\class_exists($bootstrapName)) {
            throw new ResourceNotFoundException('The application bootstrap for the RoutedRequest could not be found.  Please ensure you have created a \\' . $appNamespace . '\\Bootstrap object.');
        }
        $Bootstrap = new $bootstrapName($this->Container, $this->ClassLoader);
        if (($Bootstrap instanceof \SprayFire\Bootstrap\Bootstrapper) === false) {
            throw new ResourceNotFoundException('The application bootstrap for the RoutedRequest does not implement the appropriate interface, \\SprayFire\\Bootstrap\\Bootstrapper');
        }
        $Bootstrap->runBootstrap();
    }

}