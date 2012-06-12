<?php

/**
 * Interface for classes acting as a Service container.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Service;

/**
 * Service containers should be responsible for creating and storing services
 * used by SprayFire or, possibly, a SprayFire driven app.
 */
interface Container {

    /**
     * Should only return a service explicitly added to the Container
     *
     * Ideally this method should be what actually instantiates the service, this
     * way we ensure that each service is only created as needed.
     *
     * @param $serviceName string Java or PHP style class name representing the service
     * @return Object An object representing \a $serviceName
     * @throws SprayFire.Service.NotFoundException
     */
    public function getService($serviceName);

    /**
     * Should add the service to those allowed to be retrieved and allows the means
     * to define what parameters should be used for that service.
     *
     * @param $serviceName string A Java or PHP style class name representing the service
     * @param $parameters callable An anonymous function returning an array of constructor dependecies for $servciceName
     * @returns boolean True if added, false if not
     * @throws InvalidArgumentException if \a $callableParameters is not callable
     */
    public function addService($serviceName, $callableParameters);

    /**
     * @param $serviceName string A Java or PHP style class name representing the service
     * @return boolean True if the service has been added, false if it hasn't
     */
    public function doesServiceExist($serviceName);

}