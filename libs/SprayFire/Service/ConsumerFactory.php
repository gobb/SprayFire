<?php

/**
 * A factory that will add requested services to an object implementing the
 * SprayFire.Service.Consumer interface.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Service;

use \SprayFire\Service\Container as Container,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\Factory\BaseFactory as BaseFactory,
    \SprayFire\Exception\FatalRuntimeException as FatalException,
    \SprayFire\Service\NotFoundException as ServiceNotFoundException,
    \Artax\ReflectionPool as ReflectionCache;

abstract class ConsumerFactory extends BaseFactory {

    /**
     * @property SprayFire.Service.Container
     */
    protected $Container;

    /**
     * @param Artax.ReflectionPool $Cache
     * @param SprayFire.Service.Container $Container
     * @param SprayFire.Logging.LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullObject
     */
    public function __construct(ReflectionCache $Cache, Container $Container, LogOverseer $LogOverseer, $type, $nullObject) {
        parent::__construct($Cache, $LogOverseer, $type, $nullObject);
        $this->Container = $Container;
        $this->LogOverseer = $LogOverseer;
    }

    /**
     * @param string $className
     * @param array $parameters
     * @return Object
     */
    public function makeObject($className, array $parameters = array()) {
        $Object = parent::makeObject($className, $parameters);
        $this->addServices($Object);
        return $Object;
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
     * @param Object $Object
     * @throws SprayFire.Exception.FatalRuntimeException
     */
    protected function addServices($Object) {
        if ($Object instanceof \SprayFire\Service\Consumer) {
            try {
                $services = $Object->getRequestedServices();
                if ($this->isTraversable($services)) {
                    foreach ($services as $property => $service) {
                        $Object->giveService($property, $this->Container->getService($service));
                    }
                }
            } catch(ServiceNotFoundException $NotFoundExc) {
                throw new FatalException('The service, ' . $service . ',  for a Controller was not found.', null, $NotFoundExc);
            }
        }
    }

}