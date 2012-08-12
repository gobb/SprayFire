<?php

/**
 * A class that holds a container responsible for creating and storing services.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Service\FireService;

use \SprayFire\Service\Container as ServiceContainer,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\JavaNamespaceConverter as JavaNameConverter,
    \SprayFire\ReflectionCache as ReflectionCache;

class Container extends CoreObject implements ServiceContainer {

    /**
     * @property Artax.ReflectionPool
     */
    protected $ReflectionCache;

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
     * We are storing the empty callback for null parameters as a class property
     * so that we do not create unnecessary Closures for multiple calls with
     * null parameters.
     *
     * @property Closure
     */
    protected $emptyCallback;

    /**
     * @param Artax.ReflectionPool $ReflectionCache
     */
    public function __construct(ReflectionCache $ReflectionCache) {
        $this->ReflectionCache = $ReflectionCache;
        $this->emptyCallback = function() { return array(); };
    }

    /**
     * If a non-null parameter is passed it must be callable or an
     * InvalidArgumentException will be thrown
     *
     * @param string $serviceName
     * @param callable|null $callableParameters
     * @throws InvalidArgumentException
     */
    public function addService($serviceName, $callableParameters = null) {
        if (\is_object($serviceName)) {
            $service = $serviceName;
            $serviceName = '\\' . \get_class($service);
            $serviceKey = $this->getServiceKey($serviceName);
            $this->storedServices[$serviceKey] = $service;
        } else {
            if (\is_null($callableParameters)) {
                $callableParameters = $this->emptyCallback;
            }

            if (!\is_callable($callableParameters)) {
                throw new \InvalidArgumentException('Attempting to pass a non-callable type to a callable required parameter.');
            }
            $serviceKey = $this->getServiceKey($serviceName);

            $this->addedServices[$serviceKey] = $callableParameters;
        }
    }

    /**
     * @param string $serviceName
     * @return bool
     */
    public function doesServiceExist($serviceName) {
        $serviceKey = $this->getServiceKey($serviceName);
        return (\array_key_exists($serviceKey, $this->addedServices) || \array_key_exists($serviceKey, $this->storedServices));
    }

    /**
     * @param string $serviceName
     * @return object
     * @throws SprayFire.Service.NotFoundException
     */
    public function getService($serviceName) {
        $serviceKey = $this->getServiceKey($serviceName);
        if (\array_key_exists($serviceKey, $this->storedServices)) {
            return $this->storedServices[$serviceKey];
        }
        if (!\array_key_exists($serviceKey, $this->addedServices)) {
            throw new \SprayFire\Service\NotFoundException('A service, ' . $serviceName . ', was not properly added to the container.');
        }
        $parameterCallback = $this->addedServices[$serviceKey];
        try {
            $ReflectedService = $this->ReflectionCache->getClass($serviceName);
        } catch(\ReflectionException $NotFoundExc) {
            throw new \SprayFire\Service\NotFoundException($NotFoundExc->getMessage());
        }
        $Service = $ReflectedService->newInstanceArgs($parameterCallback());
        $this->storedServices[$serviceKey] = $Service;
        return $Service;
    }

    public function getServiceKey($serviceName) {
        return \strtolower(\preg_replace('/[^A-Za-z0-9]/', '', $serviceName));
    }

}