<?php

/**
 * A factory that ensures that a SprayFire.Logging.Logger object is returned from
 * makeObject().
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Factory.BaseFactory
 */
class LoggerFactory extends \SprayFire\Factory\BaseFactory {

    /**
     * @param $ReflectionCache Artax.ReflectionCacher
     * @param $returnType string Logger interface, defaults to SprayFire.Logging.Logger
     * @param $nullObject string A NullObject to return if errors occur, defaults
     *        to SprayFire.Logging.Logifier.NullLogger
     * @throws InvalidArgumentException
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    public function __construct(\Artax\ReflectionCacher $ReflectionCache, $returnType = 'SprayFire.Logging.Logger', $nullObject = 'SprayFire.Logging.Logifier.NullLogger') {
        parent::__construct($ReflectionCache, $returnType, $nullObject);
    }

}