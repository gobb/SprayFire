<?php

/**
 * An abstract class that provides some common functionality and groundwork
 * for concrete factory implementations.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Factory\FireFactory;

use \SprayFire\Factory\Factory as Factory,
    \SprayFire\Logging\LogOverseer as LogOverseer,
    \SprayFire\JavaNamespaceConverter as JavaNameConverter,
    \SprayFire\ObjectTypeValidator as TypeValidator,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\Factory\Exception\TypeNotFound as TypeNotFoundException,
    \SprayFire\ReflectionCache as ReflectionCache;

/**
 * All class names passed to this Factory can be passed using PHP or Java
 * style formatting.
 */
abstract class Base extends CoreObject implements Factory {

    const RETURN_NULL_OBJECT = 1;
    const THROW_EXCEPTION = 2;


    /**
     * Cache to help prevent unneeded ReflectionClasses from being created.
     *
     * @property Artax.ReflectionPool
     */
    protected $ReflectionCache;

    /**
     * @property SprayFire.Logging.LogOverseer
     */
    protected $LogOverseer;

    /**
     * @property Object
     */
    protected $NullObject;

    /**
     * @property SprayFire.Core.Util.ObjectTypeValidator
     */
    protected $TypeValidator;

    /**
     * @property int
     */
    protected $configuredErrorHandling;

    /**
     *
     * @param Artax.ReflectionPool $ReflectionCache
     * @param SprayFire.Logging.LogOveseer $LogOverseer
     * @param SprayFire.JavaNamespaceConverter $JavaNameConverter
     * @param string $returnTypeRestriction
     * @param string $nullObject
     * @throws SprayFire.Factory.Exception.TypeNotFound
     */
    public function __construct(ReflectionCache $ReflectionCache, LogOverseer $LogOverseer, $returnTypeRestriction, $nullObject) {
        $this->ReflectionCache = $ReflectionCache;
        $this->LogOverseer = $LogOverseer;
        $this->TypeValidator = $this->createTypeValidator($returnTypeRestriction);
        $this->NullObject = $this->createNullObject($nullObject);
        $this->configuredErrorHandling = self::RETURN_NULL_OBJECT;
    }

    /**
     * @return SprayFire.ObjectTypeValidator
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    protected function createTypeValidator($objectType) {
        try {
            $ReflectedType = $this->ReflectionCache->getClass($objectType);
            return new TypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new TypeNotFoundException('The injected interface or class, ' . $objectType . ', passed to ' . \get_class($this) . ' could not be loaded.', null, $ReflectExc);
        }
    }

    /**
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
                throw new \InvalidArgumentException('The given, ' . $nullObjectType . ', could not be loaded.', null, $ReflectExc);
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
     * @return Ojbect Type will be the type of the TypeValidator for the return type
     * @throws SprayFire.Factory.Exception.TypeNotFound Only thrown if configured
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
                throw new TypeNotFoundException($message, 0, $ReflectExc);
            }
        } catch (\InvalidArgumentException $InvalArgExc) {
            $this->LogOverseer->logError('The requested object, ' . $className . ', does not properly implement the appropriate type, ' . $this->TypeValidator->getType() . ', for this factory.');
            if ($this->configuredErrorHandling === self::RETURN_NULL_OBJECT) {
                return clone $this->NullObject;
            }

            if ($this->configuredErrorHandling === self::THROW_EXCEPTION) {
                throw new TypeNotFoundException($message, 0, $InvalArgExc);
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