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
    \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\Bootstrap as SFBootstrap,
    \SprayFire\StdLib as SFStdLib,
    \SprayFire\Mediator\FireMediator as FireMediator,
    \SprayFire\Exception as SFException,
    \ClassLoader\Loader as ClassLoader;

/**
 * During framework initialization, after the Request has been routed, this class
 * will ensure that your app's bootstrap has been instantiated and invoked so that
 * you may run whatever startup scripts your app needs.
 *
 * @package SprayFire
 * @subpackage Dispatcher.FireDispatcher
 *
 * @todo
 * We are still throwing \SprayFire\Dispatcher\Exception objects from this
 * implementation we need to take a look at that and figure out where those
 * exceptions should appropriately belong.
 */
class AppInitializer extends SFStdLib\CoreObject {

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
     * @throws \SprayFire\Dispatcher\Exception\BootstrapNotFound
     * @throws \SprayFire\Dispatcher\Exception\NotBootstrapperInstance
     */
    public function initializeApp($appNamespace) {
        $bootstrapName = '\\' . $appNamespace . '\\Bootstrap';
        if (!\class_exists($bootstrapName)) {
            $message = 'The application bootstrap for ' . $appNamespace . ' could not be found.  Please ensure you have created a \\' . $appNamespace . '\\Bootstrap object.';
            throw new SFDispatcher\Exception\BootstrapNotFound($message);
        }

        /** @var \SprayFire\Bootstrap\Bootstrapper $Bootstrap */
        $Bootstrap = new $bootstrapName($this->Container, $this->ClassLoader);
        if (($Bootstrap instanceof SFBootstrap\Bootstrapper) === false) {
            $message = 'The application bootstrap, ' . $bootstrapName . ', for the RoutedRequest does not implement the appropriate interface, \\SprayFire\\Bootstrap\\Bootstrapper';
            throw new SFDispatcher\Exception\NotBootstrapperInstance($message);
        }

        $Bootstrap->runBootstrap();
    }

    /**
     * Provides a convenience method to easily retrieve a \SprayFire\Mediator\Callback
     * that will call AppInitializer::initializeApp with the given $appNamespace.
     *
     * @param string $appNamespace
     * @return \SprayFire\Mediator\FireMediator\Callback
     */
    public function getAppLoadCallback($appNamespace) {
        $eventName = \SprayFire\Events::APP_LOAD;
        $Initializer = $this;
        $callback = function() use($Initializer, $appNamespace) {
            $Initializer->initializeApp($appNamespace);
        };
        return new FireMediator\Callback($eventName, $callback);
    }

}
