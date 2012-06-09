<?php

/**
 * @file
 * @brief An interface for classes acting as a Service container.
 */

namespace SprayFire\Service;

/**
 * @brief Service containers should be responsible for creating and storing services
 * used by SprayFire or, possibly, a SprayFire driven app.
 */
interface Container {

    /**
     * @brief Should only return a service explicitly added to the Container
     *
     * @details
     * Ideally this method should be what actually instantiates the service, this
     * way we ensure that each service is only created as needed.
     *
     * @param $serviceName A Java or PHP style class name representing the service
     * @return An object representing \a $serviceName
     * @throws SprayFire.Service.NotFoundException
     */
    public function getService($serviceName);

    /**
     * @brief Should add the service to those allowed to be retrieved and allows
     * the means to define what parameters should be used for that service.
     *
     * @param $serviceName A Java or PHP style class name representing the service
     * @param $parameters An array of constructor dependencies for the class
     * @returns True if added, false if not
     */
    public function addService($serviceName, array $parameters = array());

    /**
     * @param $serviceName A Java or PHP style class name representing the service
     * @return True if the service has been added, false if it hasn't
     */
    public function doesServiceExist($serviceName);

    /**
     * @param $serviceType A Java or PHP style type, meaning class or interface
     * @param $Factory A SprayFire.Factory.Factory that is responsible for creating the service
     * @return void
     */
    public function setServiceFactory($serviceType, \SprayFire\Factory\Factory $Factory);

}