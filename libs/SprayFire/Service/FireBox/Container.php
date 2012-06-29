<?php

/**
 * A class that holds a container responsible for creating and storing services.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Service\FireBox;

class Container extends \SprayFire\JavaNamespaceConverter implements \SprayFire\Service\Container {

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
     * We are storing the empty callback for null parameters as a class property
     * so that we do not create unnecessary Closures for multiple calls with
     * null parameters.
     *
     * @property Closure
     */
    protected $emptyCallback;

    /**
     * @param $ReflectionCache Artax.ReflectionCacher
     */
    public function __construct(\Artax\ReflectionCacher $ReflectionCache) {
        $this->ReflectionCache = $ReflectionCache;
        $this->emptyCallback = function() { return array(); };
    }

    /**
     * Will throw an exception if the $callableParameters is a non-callable
     * type; note that null may be passed to this parameter if there are no parameters
     * needed for the service.
     *
     * @param $serviceName mixed The class name or the object representing the service
     * @param $callableParameters callable An anonymous function returning array of service dependencies
     * @throws InvalidArgumentException
     */
    public function addService($serviceName, $callableParameters) {
        if (\is_null($callableParameters)) {
            $callableParameters = $this->emptyCallback;
        }

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
        return (\array_key_exists($serviceName, $this->addedServices) || \array_key_exists($serviceName, $this->storedServices));
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