<?php

/**
 * Base class implementating SprayFire.Service.Consumer to provide generic
 * functionality for classes consuming services.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Service\FireService;

use \SprayFire\Service,
    \SprayFire\StdLib,
    \InvalidArgumentException,
    \BadMethodCallException;

/**
 * @package SprayFire
 * @subpackage Service.FireService
 */
abstract class Consumer extends StdLib\CoreObject implements Service\Consumer {

    /**
     * @property array
     */
    protected $services = [];

    /**
     * @property array
     */
    protected $storedServices = [];

    /**
     * @param \SprayFire\Service\Builder $Builder
     */
    public function __construct(Service\Builder $Builder) {
        $Builder->buildConsumer($this);
    }

    /**
     * Override this function and add new services to the Consumer.
     *
     * @return void
     */
    public function beforeBuild() {

    }

    /**
     * Returns an array of $this->services set
     *
     * @return array
     */
    public function getRequestedServices() {
        return $this->services;
    }

    /**
     * Provides a requested service to the consumer, the service will be stored
     * in Consumer::storedServices with the $key passed.
     *
     * If the $Service is not an object of some type an exception will be thrown.
     *
     * If the $key passed has already been stored its value will be overwritten
     * by the $Service passed.
     *
     * @param string $key
     * @param object $Service
     * @throws \InvalidArgumentException
     */
    public function giveService($key, $Service) {
        $key = (string) $key;
        if (!\is_object($Service)) {
            throw new InvalidArgumentException('Services given must be an object.');
        }
        $this->storedServices[$key] = $Service;
    }

    /**
     * Method provided to easily retrieve a service based on the key stored in
     * Consumer::services
     *
     * @param string $serviceName
     * @return mixed
     */
    public function service($serviceName) {
        if (\array_key_exists($serviceName, $this->storedServices)) {
            return $this->storedServices[$serviceName];
        }
        return false;
    }

    /**
     * @param string $serviceName
     * @return object|boolean
     */
    public function __get($serviceName) {
        return $this->service($serviceName);
    }

    /**
     * @param string $serviceName
     * @param object $Service
     */
    public function __set($serviceName, $Service) {
        $this->giveService($serviceName, $Service);
    }

    /**
     * @param string $serviceName
     * @return boolean
     */
    public function __isset($serviceName) {
        return \is_object($this->service($serviceName));
    }

    /**
     * @param string $serviceName
     * @throws \BadMethodCallException
     */
    public function __unset($serviceName) {
        throw new BadMethodCallException('You may not remove a service expecting to be consumed.');
    }

}
