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
    \Artax\ReflectionPool as ReflectionPool;

class Factory extends ConsumerFactory {

    /**
     * @param Artax.ReflectionPool $Cache
     * @param SprayFire.Service.Container $Container
     * @param SprayFire.Logging.FireLogging.LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(ReflectionPool $Cache, Container $Container, LogOverseer $LogOverseer, JavaNameConverter $JavaConverter, $type = 'SprayFire.Responder.Responder', $nullType = 'SprayFire.Responder.HtmlResponder') {
        parent::__construct($Cache, $Container, $LogOverseer, $JavaConverter, $type, $nullType);
    }

}