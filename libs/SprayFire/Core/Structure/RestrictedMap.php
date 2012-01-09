<?php

/**
 * @file
 * @brief Framework's implementation of an ObjectStorage data structure.
 */

namespace SprayFire\Core\Structure;

/**
 * @brief The framework's primary implementation to store framework objects.
 *
 * @details
 * Allows for the associating of framework objects with a key and the retrieval
 * of that object using that key.  Also allows for the removal of an object
 * associated with a key and iterating over the stored objects.
 *
 * @uses SprayFire.Core.Object
 * @uses SprayFire.Core.GenericMap
 * @uses SprayFire.Core.ObjectTypeValidator
 */
class RestrictedMap extends \SprayFire\Core\Structure\GenericMap {

    /**
     * @brief A SprayFire.Core.ObjectValidator used to ensure objects added to the
     * Map are of the correct type.
     *
     * @property $TypeValidator
     */
    protected $TypeValidator;

    /**
     * @param $parentType The complete name of the class or interface that should
     *        be stored in this Map.
     * @throws TypeNotFoundException if the \a $parentType could not be loaded
     */
    public function __construct($parentType) {
        try {
            $ReflectedType = new \ReflectionClass($parentType);
            $this->TypeValidator = new \SprayFire\Core\Util\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The type passed, ' . $parentType . ', could not be found or loaded.');
        }
    }

    /**
     * @brief Determines whether or not the passed \a $Object \a is a proepr
     * object for this storage type, adds the \a $Object \a if it does and
     * throws an exception if it does not.
     *
     * @details
     * This method will only throw one exception type but that exception may
     * be thrown for one of two reasons; (1) the \a $key \a is not a valid string
     * or is empty (2) the \a $Object \a does not properly implement the type
     * injected into the constructor
     *
     * @param $key A string or numeric index
     * @param $Object Should implement SprayFire.Core.Object
     * @throws InvalidArgumentException
     * @return SprayFire.Core.Object
     */
    public function setObject($key, \SprayFire\Core\Object $Object) {
        $this->TypeValidator->throwExceptionIfObjectNotParentType($Object);
        parent::setObject($key, $Object);
    }

}