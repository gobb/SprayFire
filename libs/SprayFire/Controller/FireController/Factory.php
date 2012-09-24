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

use \SprayFire\Controller\Controller as SFController,
    \SprayFire\Service as SFService,
    \SprayFire\Logging as SFLogging,
    \SprayFire\Service\FireService as FireService,
    \SprayFire\JavaNamespaceConverter as SFJavaNameConverter,
    \SprayFire\ReflectionCache as SFReflectionCache;

/**
 * @package SprayFire
 * @subpackage Controller.FireController
 */
class Factory extends FireService\ConsumerFactory {

    /**
     * @param SprayFire.ReflectionCache $Cache
     * @param SprayFire.Service.Container $Container
     * @param SprayFire.Logging.LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(
        SFReflectionCache $Cache,
        SFService\Container $Container,
        SFLogging\LogOverseer $LogOverseer,
        $type = 'SprayFire.Controller.Controller',
        $nullType = 'SprayFire.Controller.NullObject'
    ) {
        parent::__construct($Cache, $Container, $LogOverseer, $type, $nullType);
    }

}