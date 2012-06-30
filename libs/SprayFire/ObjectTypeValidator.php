<?php

/**
 * Class used to validate object types to be the same as a constructor injection.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire;

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