<?php

/**
 * An implementation of SprayFire\Service\Builder that allows you to easily
 * give services to Consumers.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Service\FireService;

use \SprayFire\Service,
    \SprayFire\StdLib;

/**
 * Provides a concrete implementation to build services needed for a Service\Consumer.
 *
 * @package SprayFire
 * @subpackage Service.Implementation
 */
class Builder extends StdLib\CoreObject implements Service\Builder {

    /**
     * @property \SprayFire\Service\Container
     */
    protected $Container;

    /**
     * @param \SprayFire\Service\Container $Container
     */
    public function __construct(Service\Container $Container) {
        $this->Container = $Container;
    }

    /**
     * Adds the services requested to the $Consumer passed.
     *
     * This implementation does not catch the exceptions thrown by
     * \SprayFire\Service\Container.
     *
     * @param \SprayFire\Service\Consumer $Consumer
     * @return void
     * @throws \SprayFire\Service\Exception\ServiceNotFound
     * @throws \SprayFire\Service\Exception\FactoryNotRegistered
     */
    public function buildConsumer(Service\Consumer $Consumer) {
        $Consumer->beforeBuild();
        $services = $Consumer->getRequestedServices();
        if ($this->isTraversable($services)) {
            foreach ($services as $property => $service) {
                $ServiceObject = $this->Container->getService($service);
                $Consumer->giveService($property, $ServiceObject);
            }
        }
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

}
