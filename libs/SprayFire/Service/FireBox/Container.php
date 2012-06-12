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
     * Services that have already been instantiated from a call to getService()
     *
     * @property array
     */
    protected $storedServices = array();

    /**
     * @property Artax.ReflectionCacher
     */
    protected $ReflectionCache;

    /**
     * @param $ReflectionCache Artax.ReflectionCacher
     */
    public function __construct(\Artax\ReflectionCacher $ReflectionCache) {
        $this->ReflectionCache = $ReflectionCache;
    }

    /**
     *
     * @param $serviceName mixed The class name or the object representing the service
     * @param $callableParameters callable An anonymous function returning array of service dependencies
     * @throws InvalidArgumentException
     */
    public function addService($serviceName, $callableParameters) {
        if (!\is_callable($callableParameters)) {
            throw new \InvalidArgumentException('Attempt to pass a non-callable type to a callable require parameter.');
        }
        if (\is_object($serviceName)) {
            $service = $serviceName;
            $serviceName = '\\' . \get_class($service);
            $this->storedServices[$serviceName] = $service;
        } else {
            $serviceName = $this->convertJavaClassToPhpClass($serviceName);
            $this->addedServices[$serviceName] = $callableParameters;
        }

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
        $serviceName = $this->convertJavaClassToPhpClass($serviceName);
        if (\array_key_exists($serviceName, $this->storedServices)) {
            return $this->storedServices[$serviceName];
        }
        $parameterCallback = $this->addedServices[$serviceName];
        try {
            $ReflectedService = $this->ReflectionCache->getClass($serviceName);
        } catch(\ReflectionException $NotFoundExc) {
            throw new \SprayFire\Service\NotFoundException('A service, ' . $serviceName . ', was not properly added to the container.');
        }
        $Service = $ReflectedService->newInstanceArgs($parameterCallback());
        $this->storedServices[$serviceName] = $Service;
        return $Service;
    }

}