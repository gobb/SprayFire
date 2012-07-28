<?php

/**
 * Class to create and prepare a controller for use in dispatching a request.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Service\Container as Container,
    \SprayFire\Controller\Controller as Controller,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\JavaNamespaceConverter as JavaNameConverter,
    \SprayFire\Service\ConsumerFactory as ConsumerFactory,
    \SprayFire\Service\NotFoundException as ServiceNotFoundException,
    \Artax\ReflectionPool as ReflectionPool;

class Factory extends ConsumerFactory {

    public function __construct(ReflectionPool $Cache, Container $Container, LogOverseer $LogOverseer, JavaNameConverter $JavaNameConverter, $type = 'SprayFire.Controller.Controller', $nullType = 'SprayFire.Controller.NullObject') {
        parent::__construct($Cache, $Container, $LogOverseer, $JavaNameConverter, $type, $nullType);
    }

}