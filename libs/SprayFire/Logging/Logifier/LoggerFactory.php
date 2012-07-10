<?php

/**
 * A factory that ensures that a SprayFire.Logging.Logger object is returned from
 * makeObject().
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging\Logifier;

use \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\Factory\BaseFactory as BaseFactory;

class LoggerFactory extends BaseFactory {

    /**
     * @param Artax.ReflectionPool $ReflectionCache
     * @param SprayFire.Logging.LogOverseer $LogOverseer
     * @param string $returnType
     * @param string $nullObject
     */
    public function __construct(\Artax\ReflectionPool $ReflectionCache, LogOverseer $LogOverseer, $returnType = 'SprayFire.Logging.Logger', $nullObject = 'SprayFire.Logging.Logifier.NullLogger') {
        parent::__construct($ReflectionCache, $LogOverseer, $returnType, $nullObject);
    }

}