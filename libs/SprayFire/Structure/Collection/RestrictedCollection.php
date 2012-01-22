<?php

/**
 * @file
 * @brief An extension of SprayFire.Core.Structure.GenericCollection that allows
 * only a restricted type of object to be stored.
 */

namespace SprayFire\Structure\Collection;

/**
 * @brief A collection that allows any number of a restricted type of object to be
 * stored.
 *
 * @uses ReflectionClass
 * @uses SprayFire.Object
 * @uses SprayFire.Structure.Collection.GenericCollection
 * @uses SprayFire.Core.Util.ObjectTypeValidator
 */
class RestrictedCollection extends \SprayFire\Structure\Collection\GenericCollection {

    /**
     * @brief A SprayFire.Core.ObjectTypeValidator used to ensure that the passed
     * objects implement the correct interface or extends the correct class.
     *
     * @property $TypeValidator
     */
    protected $TypeValidator;

    /**
     * @param $parentType A string representing the interface or class all objects should be
     * @param $initialSize Number of buckets to initially create
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    public function __construct($parentType, $initialSize = 32) {
        parent::__construct($initialSize);
        try {
            $parentType = $this->replaceDotsWithBackSlashes($parentType);
            $ReflectedType = new \ReflectionClass($parentType);
            $this->TypeValidator = new \SprayFire\Core\Util\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The passed type, ' . $parentType . ', could not be loaded.', null, $ReflectExc);
        }
    }

    /**
     * @param $Object SprayFire.Object
     * @return numeric index of \a $Object or false if error occurred
     * @throws InvalidArgumentException if the \a $Object is not the correct type
     *         for this collection
     */
    public function addObject(\SprayFire\Object $Object) {
        $this->TypeValidator->throwExceptionIfObjectNotParentType($Object);
        return parent::addObject($Object);
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