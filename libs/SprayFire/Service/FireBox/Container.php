<?php

/**
 * A class that holds a container responsible for creating and storing services.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Service\FireBox;

class Container extends \SprayFire\Util\UtilObject implements \SprayFire\Service\Container {

    /**
     * Services and dependency callbacks added to the container
     *
     * @property array
     */
    protected $addedServices = array();

    /**
     *
     * @param $serviceName string Namespaced name of the class representing the service
     * @param $callableParameters callable An anonymous function returning array of service dependencies
     * @throws InvalidArgumentException
     */
    public function addService($serviceName, $callableParameters = null) {
        if (!\is_callable($callableParameters)) {
            throw new \SprayFire\Service\NotFoundException('Attempt to pass a non-callable type to a callable require parameter.');
        }
        $serviceName = $this->convertJavaClassToPhpClass($serviceName);
        $this->addedServices[$serviceName] = $callableParameters;
    }

    /**
     * @param $serviceName string Namespaced name of the class representing the service
     * @return boolean
     */
    public function doesServiceExist($serviceName) {
        $serviceName = $this->convertJavaClassToPhpClass($serviceName);
        return \array_key_exists($serviceName, $this->addedServices);
    }

    /**
     * @param $serviceName string Namespaced name of the class representing the service
     * @return Object Of type \a $serviceName
     * @throws SprayFire.Service.NotFoundException
     */
    public function getService($serviceName) {

    }

    /**
     * @param $serviceType string Namespaced name of the class representing the service
     * @param $Factory SprayFire.Factory.Factory
     */
    public function setServiceFactory($serviceType, \SprayFire\Factory\Factory $Factory) {

    }

}