<?php

/**
 * Base class to provide functionality for object consuming services
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Service\Firebox;

use \SprayFire\Service\Consumer as ServiceConsumer,
    \SprayFire\CoreObject as CoreObject;

abstract class Consumer extends CoreObject implements ServiceConsumer {

    /**
     * @property array
     */
    protected $services = array();

    /**
     * @property array
     */
    protected $storedServices = array();

    /**
     * Returns an array of $this->services set
     *
     * @return array
     */
    public function getRequestedServices() {
        return $this->services;
    }

    /**
     * Provides a requested service to the consumer.
     *
     * @param string $key
     * @param object $Service
     * @throws InvalidArgumentException
     */
    public function giveService($key, $Service) {
        $key = (string) $key;
        if (!\is_object($Service)) {
            throw new \InvalidArgumentException('Services added must be an object.');
        }
        $this->storedServices[$key] = $Service;
    }

    /**
     * @param string $property
     * @return object
     * @throws SprayFire.Service.NotFoundException
     */
    public function __get($property) {
        if (!$this->__isset($property)) {
            throw new \SprayFire\Service\NotFoundException('');
        }
        return $this->storedServices[$property];
    }

    /**
     * @param string $property
     * @return boolean
     */
    public function __isset($property) {
        if (!isset($this->storedServices[$property]) || !\is_object($this->storedServices[$property])) {
            return false;
        }
        return true;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    public function __set($property, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('Please use giveService() to provide access to a Service');
    }

    /**
     * @param string $property
     * @throws SprayFireException.UnsupportedOperationException
     */
    public function __unset($property) {
        throw new \SprayFire\Exception\UnsupportedOperationException('No method exists to remove a service at this time.');
    }

}