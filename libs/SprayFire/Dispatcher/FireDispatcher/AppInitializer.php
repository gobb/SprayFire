<?php

/**
 * Class used to initialize apps based on the top level namespace from a RoutedRequest.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
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
     *
     * @property SprayFire.Service.Container
     */
    protected $Container;

    /**
     * @property ClassLoader.Loader
     */
    protected $ClassLoader;

    /**
     * @property SprayFire.FileSys.PathGenerator
     */
    protected $Paths;

    /**
     *
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
     * @param SprayFire.Http.Routing.RoutedRequest $RoutedRequest
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