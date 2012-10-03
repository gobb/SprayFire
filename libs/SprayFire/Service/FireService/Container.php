<?php

/**
 * Implementation of SprayFire.Service.Container provided by the default SprayFire
 * install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Service\FireService;

use \SprayFire\Service as SFService,
    \SprayFire\Utils as SFUtils,
    \SprayFire\Service\Exception as SFServiceException,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * A very simple implementation designed to lazy-load services needed and provide
 * all consuemrs of services the same service.
 *
 * @package SprayFire
 * @subpackage Service.FireService
 */
class Container extends SFCoreObject implements SFService\Container {

    /**
     * Ensures that we are not creating uneeded Reflection objects when creating
     * services.
     *
     * @property SprayFire.Utils.ReflectionCache
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
     * @param SprayFire.Utils.ReflecationCache $ReflectionCache
     */
    public function __construct(SFUtils\ReflectionCache $ReflectionCache) {
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
     * Return whether or not the service has been added to the container.
     *
     * @param string $serviceName
     * @return bool
     */
    public function doesServiceExist($serviceName) {
        $serviceKey = $this->getServiceKey($serviceName);
        return (\array_key_exists($serviceKey, $this->addedServices)
                || \array_key_exists($serviceKey, $this->storedServices));
    }

    /**
     * Return a service object represented by $serviceName, an exception will be
     * thrown if the service requested does not exist.
     *
     * @param string $serviceName
     * @return object
     * @throws SprayFire.Service.Exception.ServiceNotFound
     */
    public function getService($serviceName) {
        $serviceKey = $this->getServiceKey($serviceName);
        if (\array_key_exists($serviceKey, $this->storedServices)) {
            return $this->storedServices[$serviceKey];
        }
        if (!\array_key_exists($serviceKey, $this->addedServices)) {
            throw new SFServiceException\ServiceNotFound('A service, ' . $serviceName . ', was not properly added to the container.');
        }
        $parameterCallback = $this->addedServices[$serviceKey];
        try {
            $ReflectedService = $this->ReflectionCache->getClass($serviceName);
        } catch(\ReflectionException $NotFoundExc) {
            throw new SFServiceException\ServiceNotFound($NotFoundExc->getMessage());
        }
        $Service = $ReflectedService->newInstanceArgs($parameterCallback());
        $this->storedServices[$serviceKey] = $Service;
        return $Service;
    }

    /**
     * Normalizes the service name, allowing it to be stored in the added and
     * stored services collections.
     *
     * @param string $serviceName
     * @return string
     */
    public function getServiceKey($serviceName) {
        return \strtolower(\preg_replace('/[^A-Za-z0-9]/', '', $serviceName));
    }

}