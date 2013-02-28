<?php

/**
 * A factory that will add requested services to an object implementing the
 * SprayFire.Service.Consumer interface.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Service\FireService;

use \SprayFire\Service as SFService,
    \SprayFire\StdLib as SFStdLib,
    \SprayFire\Logging as SFLogging,
    \SprayFire\Factory\FireFactory as FireFactory,
    \SprayFire\Exception as SFException,
    \SprayFire\Service\Exception as SFServiceException;

/**
 * @package SprayFire
 * @subpackage Service.FireService
 *
 * @deprecated
 *
 * @todo
 * We should take a look making this a Builder and not a Factory.  We are too
 * reliant on implementations to not override makeObject or to properly override
 * makeObject in such a way that the ConsumerFactory::makeObject is still invoked.
 */
abstract class ConsumerFactory extends FireFactory\Base {

    /**
     * The Container that we will be pulling services from when creating the
     * appropriate Consumer.
     *
     * @property \SprayFire\Service\Container
     */
    protected $Container;

    /**
     * @param \SprayFire\StdLib\ReflectionCache $Cache
     * @param \SprayFire\Service\Container $Container
     * @param \SprayFire\Logging\LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullObject
     */
    public function __construct(
        SFStdLib\ReflectionCache $Cache,
        SFService\Container $Container,
        SFLogging\LogOverseer $LogOverseer,
        $type,
        $nullObject
    ) {
        parent::__construct($Cache, $LogOverseer, $type, $nullObject);
        $this->Container = $Container;
    }

    /**
     * An exception can potentially be thrown from this method if the appropriate
     * services requested by $className have not been added to the Container.
     *
     * @param string $className
     * @param array $parameters
     * @return Object
     * @throws \SprayFire\Service\Exception\ServiceNotFound
     */
    public function makeObject($className, array $parameters = []) {
        $Object = parent::makeObject($className, $parameters);
        $this->addServices($Object);
        return $Object;
    }

    /**
     * Used to ensure that the values returned from Consumer::getRequestedServices
     * can be properly iterated over.
     *
     * @param mixed $type
     * @return boolean
     */
    protected function isTraversable($type) {
        if (\is_array($type) || $type instanceof \Traversable || $type instanceof \stdClass) {
            return true;
        }
        // @codeCoverageIgnoreStart
        // No reliable way to test this, if this is returned no services added to consumer
        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param \SprayFire\Service\Consumer $Object
     * @throws \SprayFire\Service\Exception\ServiceNotFound
     */
    protected function addServices(SFService\Consumer $Object) {
        $services = $Object->getRequestedServices();
        if ($this->isTraversable($services)) {
            foreach ($services as $property => $service) {
                $ServiceObject = $this->Container->getService($service);
                $Object->giveService($property, $ServiceObject);
            }
        }
    }

}
