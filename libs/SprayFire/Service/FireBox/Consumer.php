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

    public function service($serviceName) {
        if (\array_key_exists($serviceName, $this->storedServices)) {
            return $this->storedServices[$serviceName];
        }
        return false;
    }

}