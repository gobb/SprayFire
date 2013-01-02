<?php

/**
 * Abstract implementation of SprayFire.Factory.Factory that will allow for the
 * creation of generic SprayFire objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Factory\FireFactory;

use \SprayFire\Factory as SFFactory,
    \SprayFire\Logging as SFLogging,
    \SprayFire\Utils as SFUtils,
    \SprayFire\CoreObject as SFCoreObject,
    \ReflectionException as ReflectionException,
    \InvalidArgumentException as InvalidArgumentException;

/**
 * All Factory implementations provided by the default SprayFire install will
 * extend from this Factory.
 *
 * @package SprayFire
 * @subpackage Factory.FireFactory
 *
 * @todo
 * Look at implementing a strategy pattern for dealing with Factory error handling.
 */
abstract class Base extends SFCoreObject implements SFFactory\Factory {
    /**
     * Cache to help prevent unneeded ReflectionClass from being created.
     *
     * @property \SprayFire\ReflectionCache
     */
    protected $ReflectionCache;

    /**
     * Ensures that we can log messages about failed object creation.
     *
     * @property \SprayFire\Logging\LogOverseer
     */
    protected $LogOverseer;

    /**
     * The type of object that should be returned if an error is encountered with
     * the creation of the requested object.
     *
     * @property Object
     */
    protected $NullObject;

    /**
     * A helper object to ensure that the appropriate types are returned from
     * Base::makeObject
     *
     * @property \SprayFire\Core\Util\ObjectTypeValidator
     */
    protected $TypeValidator;

    /**
     * @param \SprayFire\Utils\ReflectionCache $Cache
     * @param \SprayFire\Logging\LogOverseer $LogOverseer
     * @param string $returnTypeRestriction
     * @param string $nullObject
     * @throws \SprayFire\Factory\Exception\TypeNotFound
     */
    public function __construct(SFUtils\ReflectionCache $Cache, SFLogging\LogOverseer $LogOverseer, $returnTypeRestriction, $nullObject) {
        $this->ReflectionCache = $Cache;
        $this->LogOverseer = $LogOverseer;
        $this->TypeValidator = $this->createTypeValidator($returnTypeRestriction);
        $this->NullObject = $this->createNullObject($nullObject);
    }

    /**
     * Ensures that the appropriate type validator is created for this factory.
     *
     * We are not injecting this as a dependency because this is really an implementation
     * detail and shouldn't be exposed to the outside world, we just care about
     * validating types correctly.
     *
     * @param string $objectType
     * @return \SprayFire\ObjectTypeValidator
     * @throws \SprayFire\Factory\Exception\TypeNotFound
     */
    protected function createTypeValidator($objectType) {
        try {
            $ReflectedType = $this->ReflectionCache->getClass($objectType);
            return new ObjectTypeValidator($ReflectedType);
        } catch (ReflectionException $ReflectExc) {
            $message = 'The injected interface or class, ' . $objectType . ', passed to ' . \get_class($this) . ' could not be loaded.';
            throw new SFFactory\Exception\TypeNotFound($message, null, $ReflectExc);
        }
    }

    /**
     * Creates a Null Object implementation and ensures that the implementation
     * is of the appropriate type for the factory.
     *
     * @param string $nullObjectType
     * @return Object instanceof $this->nullObjectType
     * @throws  \SprayFire\Factory\Exception\TypeNotFound
     */
    protected function createNullObject($nullObjectType) {
        $NullObject = $nullObjectType;
        if (!\is_object($NullObject)) {
            try {
                $ReflectedNullObject = $this->ReflectionCache->getClass($NullObject);
                $NullObject = $ReflectedNullObject->newInstance();
            } catch (ReflectionException $ReflectExc) {
                $message = 'The given null object, ' . $nullObjectType . ', could not be loaded.';
                throw new SFFactory\Exception\TypeNotFound($message, null, $ReflectExc);
            }
        }
        $this->TypeValidator->throwExceptionIfObjectNotParentType($NullObject);
        return $NullObject;
    }

    /**
     * If there is a problem creating the given object a clone of the NullObject
     * prototype for this factory will be returned.
     *
     * @param string $className
     * @param array $parameters
     * @return Object Type restricted by Factory constructor parameters
     */
    public function makeObject($className, array $parameters = array()) {
        try {
            $ReflectedClass = $this->ReflectionCache->getClass($className);
            $returnObject = $ReflectedClass->newInstanceArgs($parameters);
            $this->TypeValidator->throwExceptionIfObjectNotParentType($returnObject);
            return $returnObject;
        } catch (ReflectionException $ReflectExc) {
            $message = 'There was an error creating the requested object, ' . $className . '.  It likely does not exist.';
        } catch (InvalidArgumentException $InvalArgExc) {
            $message = 'The requested object, ' . $className . ', does not properly implement the appropriate type, ' . $this->getObjectType() . ', for this factory.';
        }
        if (!empty($message)) {
            $this->LogOverseer->logError($message);
        }

        return $this->NullObject;
    }

    /**
     * Will return the type created by the factory as a PHP namespaced type.
     *
     * @return string
     */
    public function getObjectType() {
        return '\\' . $this->TypeValidator->getType();
    }

    /**
     * Will return the type of Null Object used by the factory as a PHP namespaced
     * type.
     *
     * @return string
     */
    public function getNullObjectType() {
        return '\\' . \get_class($this->NullObject);
    }

}
