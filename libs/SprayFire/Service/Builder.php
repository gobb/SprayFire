<?php

/**
 * An interface for building a SprayFire\Service\Consumer implementation, after
 * the Consumer is built it should be populated with the requested services.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Service;

use \SprayFire\Object;

/**
 * An interface to abstract providing services to a Consumer from a Container
 * implementation.
 *
 * The idea behind this interface is that we can provide a way to get these
 * services in a way that doesn't simply expose the Container to all the things.
 * You have to explicitly request services and you can't just get whatever service
 * you want...unless you jump through a few hoops to do so.
 *
 * You can extend this object, instantiate it and pass into constructor or use
 * it in some factory object creation process.
 *
 * @package SprayFire
 * @subpackage Service.Interface
 */
interface Builder extends Object {

    /**
     * Should ensure that the requested services from the passed $Consumer are
     * added and if it cannot be added an appropriate exception should be thrown.
     *
     * @param \SprayFire\Service\Consumer $Consumer
     * @return void
     * @throws \SprayFire\Service\Exception\ServiceNotFound
     * @throws \SprayFire\Service\Exception\FactoryNotRegistered
     */
    public function buildConsumer(Consumer $Consumer);

}
