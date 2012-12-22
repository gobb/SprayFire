<?php

/**
 * A Factory for creating SprayFire.Responder.Responder implementations and
 * assuring that they are constructed with the appropriate services attached.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Logging as SFLogging,
    \SprayFire\Service as SFService,
    \SprayFire\Utils as SFUtils,
    \SprayFire\Service\FireService as FireService;

/**
 * @package SprayFire
 * @subpackage Responder.FireResponder
 */
class Factory extends FireService\ConsumerFactory {

    /**
     * @param \SprayFire\Utils\ReflectionCache $Cache
     * @param \SprayFire\Service\Container $Container
     * @param \SprayFire\Logging\LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(
        SFUtils\ReflectionCache $Cache,
        SFService\Container $Container,
        SFLogging\LogOverseer $LogOverseer,
        $type = 'SprayFire.Responder.Responder',
        $nullType = 'SprayFire.Responder.FireResponder.Html'
    ) {
        parent::__construct($Cache, $Container, $LogOverseer, $type, $nullType);
    }

}
