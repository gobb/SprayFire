<?php

/**
 * Class to create and prepare a controller for use in dispatching a request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller;

use \SprayFire\Service\Container as Container,
    \SprayFire\Factory\BaseFactory as BaseFactory,
    \Artax\ReflectionPool as ReflectionPool;

class Factory extends BaseFactory {

    /**
     * @property SprayFire.Service.Container
     */
    protected $Container;

    /**
     * @param Artax.ReflectionPool $ReflectionCache
     * @param SprayFire.Service.FireBox.Container $Container
     * @param string $type
     * @param string $null
     */
    public function __construct(ReflectionPool $ReflectionCache, Container $Container, $type = 'SprayFire.Controller.Controller', $null = 'SprayFire.Controller.NullObject') {
        parent::__construct($ReflectionCache, $type, $null);
        $this->Container = $Container;
    }

    /**
     * @param string $className
     * @param array $parameters
     * @return SprayFire.Controller.Controller
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    public function makeObject($className, array $parameters = array()) {
        $Controller = parent::makeObject($className);
        $this->addServices($Controller);
        return $Controller;
    }

    /**
     * @param mixed $type
     * @return boolean
     */
    protected function isTraversable($type) {
        if (\is_array($type) || $type instanceof \Traversable || $type instanceof \stdClass) {
            return true;
        }
        return false;
    }

    /**
     * @param SprayFire.Controller.Controller $Controller
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    protected function addServices(\SprayFire\Controller\Controller $Controller) {

        try {
            $services = $Controller->getServices();
            if ($this->isTraversable($services)) {
                foreach ($services as $property => $service) {
                    $Controller->$property = $this->Container->getService($service);
                }
            }
        } catch(\SprayFire\Service\NotFoundException $NotFoundExc) {
            throw new \SprayFire\Exception\FatalRuntimeException('The service, ' . $service . ',  for a Controller was not found.', null, $NotFoundExc);
        }

    }

}