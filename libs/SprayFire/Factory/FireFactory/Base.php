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
    \SprayFire\JavaNamespaceConverter as SFJavaNameConverter,
    \SprayFire\CoreObject as SFCoreObject,
    \SprayFire\ReflectionCache as SFReflectionCache,
    \SprayFire\Exception as SFException;

/**
 * All Factory implementations provided by the default SprayFire install will
 * extend from this Factory.
 *
 * @package SprayFire
 * @subpackage Factory.FireFactory
 *
 * @TODO
 * Look at implementing a strategy pattern for dealing with Factory error handling.
 */
abstract class Base extends SFCoreObject implements SFFactory\Factory {

    const RETURN_NULL_OBJECT = 1;
    const THROW_EXCEPTION = 2;

    /**
     * Cache to help prevent unneeded ReflectionClass from being created.
     *
     * @property SprayFire.ReflectionCache
     */
    protected $ReflectionCache;

    /**
     * Ensures that we can log messages about failed object creation.
     *
     * @property SprayFire.Logging.LogOverseer
     */
    protected $LogOverseer;

    /**
     * The type of object that should be returned if an error is encountered with
     * the creation of the requested object.
     *
     * This object is only returned if the error handling configuration is set
     * to Base::RETURN_NULL_OBJECT.
     *
     * @property Object
     */
    protected $NullObject;

    /**
     * A helper object to ensure that the appropriate types are returned from
     * Base::makeObject
     *
     * @property SprayFire.Core.Util.ObjectTypeValidator
     */
    protected $TypeValidator;

    /**
     * Stores the values for the error handling requested for the creation of
     * this object.
     *
     * @property int
     */
    protected $configuredErrorHandling;

    /**
     *
     * @param SprayFire.ReflectionCache $ReflectionCache
     * @param SprayFire.Logging.LogOveseer $LogOverseer
     * @param SprayFire.JavaNamespaceConverter $JavaNameConverter
     * @param string $returnTypeRestriction
     * @param string $nullObject
     * @throws InvalidArgumentException
     */
    public function __construct(SFReflectionCache $ReflectionCache, SFLogging\LogOverseer $LogOverseer, $returnTypeRestriction, $nullObject) {
        $this->ReflectionCache = $ReflectionCache;
        $this->LogOverseer = $LogOverseer;
        $this->TypeValidator = $this->createTypeValidator($returnTypeRestriction);
        $this->NullObject = $this->createNullObject($nullObject);
        $this->configuredErrorHandling = self::RETURN_NULL_OBJECT;
    }

    /**
     * Ensures that the appropriate type validator is created for this factory.
     *
     * We are not injecting this as a dependency because this is really an implementation
     * detail and shouldn't be exposed to the outside world, we just care about
     * validating types correctly.
     *
     * @return SprayFire.ObjectTypeValidator
     * @throws InvalidArgumentException
     */
    protected function createTypeValidator($objectType) {
        try {
            $ReflectedType = $this->ReflectionCache->getClass($objectType);
            return new ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \InvalidArgumentException('The injected interface or class, ' . $objectType . ', passed to ' . \get_class($this) . ' could not be loaded.', null, $ReflectExc);
        }
    }

    /**
     * Creates a Null Object implementation and ensures that the implementation
     * is of the appropriate type for the factory.
     *
     * @return Object instanceof $this->nullObjectType
     * @throws InvalidArgumentException
     */
    protected function createNullObject($nullObjectType) {
        $NullObject = $nullObjectType;
        if (!\is_object($NullObject)) {
            try {
                $ReflectedNullObject = $this->ReflectionCache->getClass($NullObject);
                $NullObject = $ReflectedNullObject->newInstance();
            } catch (\ReflectionException $ReflectExc) {
                throw new \InvalidArgumentException('The given null object, ' . $nullObjectType . ', could not be loaded.', null, $ReflectExc);
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
     * @throws SprayFire.Exception.ResourceNotFound Only thrown if configured
     */
    public function makeObject($className, array $parameters = array()) {
        try {
            $ReflectedClass = $this->ReflectionCache->getClass($className);
            $returnObject = $ReflectedClass->newInstanceArgs($parameters);
            $this->TypeValidator->throwExceptionIfObjectNotParentType($returnObject);
            return $returnObject;
        } catch (\ReflectionException $ReflectExc) {
            $message = 'There was an error creating the requested object, ' . $className . '.  It likely does not exist.';
            $this->LogOverseer->logError($message);

            if ($this->configuredErrorHandling === self::RETURN_NULL_OBJECT) {
                return clone $this->NullObject;
            }

            if ($this->configuredErrorHandling === self::THROW_EXCEPTION) {
                throw new SFException\ResourceNotFound($message, 0, $ReflectExc);
            }
        } catch (\InvalidArgumentException $InvalArgExc) {
            $this->LogOverseer->logError('The requested object, ' . $className . ', does not properly implement the appropriate type, ' . $this->TypeValidator->getType() . ', for this factory.');
            if ($this->configuredErrorHandling === self::RETURN_NULL_OBJECT) {
                return clone $this->NullObject;
            }

            if ($this->configuredErrorHandling === self::THROW_EXCEPTION) {
                throw new SFException\ResourceNotFound($message, 0, $InvalArgExc);
            }
        }
    }

    /**
     * If the $methodType is an appropriate method the factory is configured to
     * handle it will alter the behavior of the factory when an error is encountered
     * resulting in an object not able to be created.
     *
     * @param int $methodType
     * @return boolean
     */
    public function setErrorHandlingMethod($methodType) {
        $whiteListedMethods = array(
            self::RETURN_NULL_OBJECT,
            self::THROW_EXCEPTION
        );
        if (\in_array($methodType, $whiteListedMethods, true)) {
            $this->configuredErrorHandling = $methodType;
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getObjectType() {
        return '\\' . $this->TypeValidator->getType();
    }

    /**
     * @return string
     */
    public function getNullObjectType() {
        return '\\' . \get_class($this->NullObject);
    }

}