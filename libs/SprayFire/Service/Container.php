<?php

/**
 * Interface for classes that are inteded to store services for later use throughout
 * the framework and your applications.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Service;

use \SprayFire\Factory as SFFactory,
    \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Service
 */
interface Container extends SFObject {

    /**
     * Will return the object represented by $serviceName or throw an exception
     * if that service does not exist.
     *
     * @param string $serviceName
     * @return object
     * @throws \SprayFire\Service\NotFoundException
     */
    public function getService($serviceName);

    /**
     * Add a service to the container, $callableParameters for constructor should
     * be a callable function that returns the appropriate dependencies for the service.
     *
     * If a valid $factoryKey has been passed the service should be created with
     * that Factory. An invalid key should result in an exception being thrown.
     *
     * @param string $serviceName
     * @param callable|null $callableParameters
     * @param null $factoryKey
     * @return void
     * @param string|null $factoryKey
     */
    public function addService($serviceName, $callableParameters = null, $factoryKey = null);

    /**
     * Should return whether or not a given service has been added to the container.
     *
     * @param string $serviceName
     * @return bool
     */
    public function doesServiceExist($serviceName);

    /**
     * Will register a Factory with a given key, if that key is used in addService
     * calls then the service will be created by that Factory.
     *
     * @param string $factoryKey
     * @param \SprayFire\Factory\Factory $Factory
     */
    public function registerFactory($factoryKey, SFFactory\Factory $Factory);

}
