<?php

/**
 * @file
 * @brief A factory that ensures that a SprayFire.Logging.Logger object is returned
 * from makeObject().
 */

namespace SprayFire\Logging\Logifier;

/**
 * @uses SprayFire.Factory.BaseFactory
 */
class LoggerFactory extends \SprayFire\Factory\BaseFactory {

    /**
     * @param $returnType The Logger interface, defaults to SprayFire.Logging.Logger
     * @param $nullPrototype A NullObject prototype to return if errors occur, defaults
     *        to SprayFire.Logging.Logifier.NullLogger
     * @throws InvalidArgumentException
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    public function __construct($returnType = 'SprayFire.Logging.Logger', $nullPrototype = 'SprayFire.Logging.Logifier.NullLogger') {
        parent::__construct($returnType, $nullPrototype);
    }

    /**
     * @param $className A Java or PHP style namespaced class
     * @param $options An array of arguments to pass to constructor of \a $className
     * @return SprayFire.Logging.Logger object
     */
    public function makeObject($className, array $options = array()) {
        return $this->createObject($className, $options);
    }

}