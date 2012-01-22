<?php

/**
 * @file
 * @brief Framework's implementation of an ObjectStorage data structure.
 */

namespace SprayFire\Structure\Map;

/**
 * @brief The framework's primary implementation to store framework objects.
 *
 * @details
 * Allows for the associating of framework objects with a key and the retrieval
 * of that object using that key.  Also allows for the removal of an object
 * associated with a key and iterating over the stored objects.
 *
 * @uses SprayFire.Object
 * @uses SprayFire.Structure.Map.GenericMap
 * @uses SprayFire.Util.ObjectTypeValidator
 */
class RestrictedMap extends \SprayFire\Structure\Map\GenericMap {

    /**
     * @brief A SprayFire.Util.ObjectValidator used to ensure objects added to the
     * Map are of the correct type.
     *
     * @property $TypeValidator
     */
    protected $TypeValidator;

    /**
     * @param $parentType A Java or PHP-style namespaced class that objects in this
     *        map should be restricted to.
     * @throws SprayFire.Exception.TypeNotFoundException if the \a $parentType could
     *         not be loaded
     */
    public function __construct($parentType) {
        try {
            $parentType = $this->replaceDotsWithBackSlashes($parentType);
            $ReflectedType = new \ReflectionClass($parentType);
            $this->TypeValidator = new \SprayFire\Util\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The type passed, ' . $parentType . ', could not be found or loaded.', null, $ReflectExc);
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
     * @param $Object An object to assign to \a $key
     * @return SprayFire.Object
     * @throws InvalidArgumentException
     */
    public function setObject($key, \SprayFire\Object $Object) {
        $this->TypeValidator->throwExceptionIfObjectNotParentType($Object);
        parent::setObject($key, $Object);
    }

    /**
     * @param $className A Java-style namespaced class
     * @return A PHP-style namespaced class
     */
    protected function replaceDotsWithBackSlashes($className) {
        $backSlash = '\\';
        $dot = '.';
        if (\strpos($className, $dot) !== false) {
            $className = \str_replace($dot, $backSlash, $className);
        }
        return $backSlash . \trim($className, '\\ ');
    }

}