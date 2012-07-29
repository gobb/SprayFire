<?php

/**
 * A factory that ensures that a SprayFire.Logging.Logger object is returned from
 * makeObject().
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\Factory\FireFactory\Base as BaseFactory,
    \SprayFire\JavaNamespaceConverter as JavaNameConverter;

class LoggerFactory extends BaseFactory {

    /**
     * @param Artax.ReflectionPool $ReflectionCache
     * @param SprayFire.Logging.LogOverseer $LogOverseer
     * @param SprayFire.JavaNamespaceConverter $JavaConverter
     * @param string $returnType
     * @param string $nullObject
     */
    public function __construct(\Artax\ReflectionPool $ReflectionCache, LogOverseer $LogOverseer, JavaNameConverter $JavaConverter, $returnType = 'SprayFire.Logging.Logger', $nullObject = 'SprayFire.Logging.Logifier.NullLogger') {
        parent::__construct($ReflectionCache, $LogOverseer, $JavaConverter, $returnType, $nullObject);
    }

}