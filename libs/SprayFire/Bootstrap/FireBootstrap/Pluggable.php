<?php

/**
 * Abstract \SprayFire\Bootstrap\Bootstrapper that provides easy access to service
 * containers and autoloading setup for apps, plugins and optional SprayFire modules.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Bootstrap\FireBootstrap;

use \SprayFire\Bootstrap as SFBootstrap,
    \SprayFire\Service as SFService,
    \SprayFire\CoreObject as SFCoreObject,
    \ClassLoader\Loader as ClassLoader;

/**
 * Provides a generic Bootstrapper that will allow applications, plugins and third
 * party libraries to easily integrate their own startup procedures into the
 * framework.
 *
 * @package SprayFire
 * @subpackage Bootstrap.FireBootstrap
 */
abstract class Pluggable extends SFCoreObject implements SFBootstrap\Bootstrapper {

    /**
     * Container used by framework to hold your services; add services that your
     * application needs when calling runBootstrap().
     *
     * @property \SprayFire\Service\Container
     */
    protected $Container;

    /**
     * ClassLoader library that allows you to setup autoloading for whatever third
     * party libraries your app may be using.
     *
     * @property \ClassLoader\Loader
     */
    protected $ClassLoader;

    /**
     *
     *
     * @param \SprayFire\Service\Container $Container
     * @param \ClassLoader\Loader $ClassLoader
     */
    public function __construct(SFService\Container $Container, ClassLoader $ClassLoader) {
        $this->Container = $Container;
        $this->ClassLoader = $ClassLoader;
    }

}
