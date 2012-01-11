<?php

/**
 * @file
 * @brief A file holding a class that holds a set of objects; restricted to a specific
 * type.
 */

namespace SprayFire\Structure\Collection;

/**
 * @brief This class will hold a collection of unique objects and ensure that those
 * objects are of a specific interface or class.
 *
 * @uses ReflectionClass
 * @uses InvalidArgumentException
 * @uses SprayFire.Structure.GenericSet
 * @uses SprayFire.Core.Util.ObjectTypeValidator
 * @uses SprayFire.Exception.TypeNotFoundException
 */
class RestrictedSet extends \SprayFire\Structure\Collection\GenericSet {

    /**
     * @brief Used to validate that an object added to the set adheres to a specific
     * type.
     *
     * @propery SprayFire.Core.Util.ObjectTypeValidator
     */
    protected $TypeValidator;

    /**
     * @param $parentType A string representing the interface or class that this
     *        set should be restricted to
     * @param $initialSize The number of buckets the set should be initialized to
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    public function __construct($parentType, $initialSize = 32) {
        parent::__construct($initialSize);
        try {
            $parentType = $this->replaceDotsWithBackSlashes($parentType);
            $ReflectedType = new \ReflectionClass($parentType);
            $this->TypeValidator = new \SprayFire\Core\Util\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The parent type for this set, ' . $parentType . ', could not be loaded.', null, $ReflectExc);
        }

    }

    /**
     * @param $Object SprayFire.Core.Object
     * @return The numeric index or false on failure
     * @throws InvalidArgumentException
     */
    public function addObject(\SprayFire\Core\Object $Object) {
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