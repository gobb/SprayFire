<?php

/**
 * Class used to validate object types to be the same as a constructor injection.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Factory\FireFactory;

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
            $message = 'The object, ' . \get_class($Object) . ', does not properly implement ' . $this->getType();
            throw new \InvalidArgumentException($message);
        }
    }

    public function getType() {
        return $this->ReflectedParentType->getName();
    }

}