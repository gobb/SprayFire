<?php

/**
 * @file
 * @brief Holds a class used to determine if a SprayFire.Core.Object implements or
 * extends a specific type.
 */

namespace SprayFire\Util;

/**
 * @brief An internally used object in Restricted structures to determine if the
 * passed objects implement the correct type.
 */
class ObjectTypeValidator {

    /**
     * @brief Holds a ReflectionClass of the data type that should be implemented by objects
     * being added to this storage.
     *
     * @property ReflectionClass
     */
    protected $ReflectedParentType;

    /**
     * @param $ReflectedType \ReflectionClass
     */
    public function __construct(\ReflectionClass $ReflectedType) {
        $this->ReflectedParentType = $ReflectedType;
    }

    /**
     * @param $Object SprayFire.Core.Object
     * @throws InvalidArgumentException
     */
    public function throwExceptionIfObjectNotParentType($Object) {
        if (!\is_a($Object, $this->ReflectedParentType->getName())) {
            throw new \InvalidArgumentException('The value being set does not properly implement the parent type for this store.');
        }
    }

}