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
        if (!$this->isObjectParentType($Object)) {
            throw new \InvalidArgumentException('The value being set does not properly implement the parent type for this store.');
        }
    }

    /**
     * @brief Determines whether or not the passed \a $Object \a implements
     * the parent type set by the constructor dependency.
     *
     * @details
     * The objet is considered valid if any of the following are true:
     *
     * 1) Implements the interface passed in the constructor
     * 2) Is an instance of the class passed in the constructor
     * 3) Is a subclass of the class passed in the constructor
     *
     * @param $Object SprayFire.Core.Object
     * @return boolean
     */
    protected function isObjectParentType($Object) {
        $isValid = false;
        $parentName = $this->ReflectedParentType->getName();
        try {
            $ReflectedObject = new \ReflectionClass($Object);
            if ($this->ReflectedParentType->isInterface()) {
                if ($ReflectedObject->implementsInterface($parentName)) {
                    $isValid = true;
                }
            } else {
                if ($ReflectedObject->getName() === $parentName || $ReflectedObject->isSubclassOf($parentName)) {
                    $isValid = true;
                }
            }
        } catch (\ReflectionException $ReflectionExc) {
            // @codeCoverageIgnoreStart
            // The possibility of this being thrown should be very, very slim as
            // a properly instantiated object must be passed, and thus must be
            // available for reflection...this is a fail safe to not leak an
            // unnecessary exception
            error_log($ReflectionExc->getMessage());
            // @codeCoverageIgnoreEnd
        }
        return $isValid;
    }

}