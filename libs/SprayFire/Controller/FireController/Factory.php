<?php

/**
 * Class to create and prepare a controller for use in dispatching a request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Service\Container as Container,
    \SprayFire\Controller\Controller as Controller,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\JavaNamespaceConverter as JavaNameConverter,
    \SprayFire\Service\FireService\ConsumerFactory as ConsumerFactory,
    \SprayFire\Service\NotFoundException as ServiceNotFoundException,
    \SprayFire\ReflectionCache as ReflectionCache;

class Factory extends ConsumerFactory {

    public function __construct(ReflectionCache $Cache, Container $Container, LogOverseer $LogOverseer, $type = 'SprayFire.Controller.Controller', $nullType = 'SprayFire.Controller.NullObject') {
        parent::__construct($Cache, $Container, $LogOverseer, $type, $nullType);
    }

}