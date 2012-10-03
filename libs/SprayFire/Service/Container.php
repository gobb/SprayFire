<?php

/**
 * Interface for classes that are inteded to store services for later use throughout
 * the framework and your applications.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Service;

use \SprayFire\Object as SFObject;

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
     * @throws SprayFire.Service.NotFoundException
     */
    public function getService($serviceName);

    /**
     * Add a service to the container, $callableParameters should be a callable
     * function that returns the appropriate dependencies for the service.
     *
     * @param string $serviceName
     * @param callable|null $callableParameters
     */
    public function addService($serviceName, $callableParameters = null);

    /**
     * Should return whether or not a given service has been added to the container.
     *
     * @param string $serviceName
     * @return bool
     */
    public function doesServiceExist($serviceName);

}