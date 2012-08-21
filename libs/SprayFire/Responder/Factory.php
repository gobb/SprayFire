<?php

/**
 * A Factory for creating Responders and assuring that they are constructed with
 * the appropriate services attached.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Responder;

use \SprayFire\Service\Container as Container,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\Service\FireService\ConsumerFactory as ConsumerFactory,
    \SprayFire\JavaNamespaceConverter as JavaNameConverter,
    \SprayFire\ReflectionCache as ReflectionCache;

class Factory extends ConsumerFactory {

    /**
     * @param Artax.ReflectionPool $Cache
     * @param SprayFire.Service.Container $Container
     * @param SprayFire.Logging.FireLogging.LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(ReflectionCache $Cache, Container $Container, LogOverseer $LogOverseer, $type = 'SprayFire.Responder.Responder', $nullType = 'SprayFire.Responder.FireResponder.Html') {
        parent::__construct($Cache, $Container, $LogOverseer, $type, $nullType);
    }

}