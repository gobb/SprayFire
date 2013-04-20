<?php

/**
 * A Factory for creating SprayFire.Responder.Responder implementations and
 * assuring that they are constructed with the appropriate services attached.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Logging,
    \SprayFire\Service,
    \SprayFire\StdLib,
    \SprayFire\Factory\FireFactory,
    \SprayFire\Service\FireService;

/**
 * @package SprayFire
 * @subpackage Responder.Implementation
 */
class Factory extends FireFactory\Base {

    /**
     * @property \SprayFire\Service\Builder
     */
    protected $Builder;

    /**
     * @param \SprayFire\Service\Builder $Builder
     * @param \SprayFire\StdLib\ReflectionCache $Cache
     * @param \SprayFire\Logging\LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(
        Service\Builder $Builder,
        StdLib\ReflectionCache $Cache,
        Logging\LogOverseer $LogOverseer,
        $type = 'SprayFire.Responder.Responder',
        $nullType = 'SprayFire.Responder.FireResponder.Html'
    ) {
        parent::__construct($Cache, $LogOverseer, $type, $nullType);
        $this->Builder = $Builder;
    }

    /**
     * @param string $className
     * @param array $parameters
     * @return Object|\SprayFire\Service\Consumer
     */
    public function makeObject($className, array $parameters = []) {
        return parent::makeObject($className, [$this->Builder]);
    }

}
