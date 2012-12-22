<?php

/**
 * Class used to validate object types to be the same as a constructor injection.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Factory\FireFactory;

use \ReflectionClass as ReflectionClass,
    \InvalidArgumentException as InvalidArgumentException;

/**
 * This is a package private class and is intended to be used only by implementations
 * in module SprayFire.Factory.FireFactory
 *
 * @package SprayFire
 * @subpackage Factory.FireFactory
 */
class ObjectTypeValidator {

    /**
     * A ReflectionClass of the data type that should be implemented by objects.
     *
     * @property ReflectionClass
     */
    protected $ReflectedParentType;

    /**
     * @param ReflectionClass $ReflectedType
     */
    public function __construct(ReflectionClass $ReflectedType) {
        $this->ReflectedParentType = $ReflectedType;
    }

    /**
     * @param Object $Object
     * @throws InvalidArgumentException
     */
    public function throwExceptionIfObjectNotParentType($Object) {
        if (!\is_a($Object, $this->ReflectedParentType->getName())) {
            $message = 'The object, ' . \get_class($Object) . ', does not properly implement ' . $this->getType();
            throw new InvalidArgumentException($message);
        }
    }

    /**
     * Will return the name of the type this instance will validate on in a
     * PHP namespaced format.
     *
     * @return string
     */
    public function getType() {
        return $this->ReflectedParentType->getName();
    }

}