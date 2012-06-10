<?php

/**
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

    public function addService($serviceName, $callableParameters = null) {
    }

    public function doesServiceExist($serviceName) {
        return \array_key_exists($serviceName, $this->addedServices);
    }

    public function getService($serviceName) {

    }

    public function setServiceFactory($serviceType, \SprayFire\Factory\Factory $Factory) {

    }

}