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
    \SprayFire\Exception\TypeNotFoundException as TypeNotFoundException,
    \Artax\ReflectionPool as ReflectionPool;

/**
 * All class names passed to this Factory can be passed using PHP or Java
 * style formatting.
 */
abstract class Base extends CoreObject implements Factory {

    /**
     * Keys stored in this array should be a PHP-style namespaced class.
     *
     * @property array
     */
    protected $blueprints = array();

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

    protected $JavaNameConverter;

    /**
     * @property Object
     */
    protected $NullObject;

    /**
     * @property SprayFire.Core.Util.ObjectTypeValidator
     */
    protected $TypeValidator;

    /**
     * Should be a properly PHP namespaced class
     *
     * @property string
     */
    protected $objectType;

    /**
     * Should be a properly PHP namespaced class
     *
     * @property string
     */
    protected $nullObjectType;

    /**
     *
     * @param Artax.ReflectionPool $ReflectionCache
     * @param SprayFire.Logging.LogOveseer $LogOverseer
     * @param string $returnTypeRestriction
     * @param string $nullObject
     */
    public function __construct(ReflectionPool $ReflectionPool, LogOverseer $LogOverseer, JavaNameConverter $JavaNameConverter, $returnTypeRestriction, $nullObject) {
        $this->ReflectionCache = $ReflectionPool;
        $this->LogOverseer = $LogOverseer;
        $this->JavaNameConverter = $JavaNameConverter;
        $this->objectType = $JavaNameConverter->convertJavaClassToPhpClass($returnTypeRestriction);
        $this->nullObjectType = $JavaNameConverter->convertJavaClassToPhpClass($nullObject);
        $this->TypeValidator = $this->createTypeValidator();
        $this->NullObject = $this->createNullObject();
    }

    /**
     * @return SprayFire.ObjectTypeValidator
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    protected function createTypeValidator() {
        try {
            $ReflectedType = $this->ReflectionCache->getClass($this->objectType);
            $TypeValidator = new TypeValidator($ReflectedType);
            return $TypeValidator;
        } catch (\ReflectionException $ReflectExc) {
            throw new TypeNotFoundException('The injected interface or class, ' . $this->objectType . ', could not be found.', null, $ReflectExc);
        }
    }

    /**
     * @return Object instanceof $this->nullObjectType
     * @throws InvalidArgumentException
     */
    protected function createNullObject() {
        $NullObject = $this->nullObjectType;
        if (!\is_object($NullObject)) {
            try {
                $ReflectedNullObject = $this->ReflectionCache->getClass($NullObject);
                $NullObject = $ReflectedNullObject->newInstance();
            } catch (\ReflectionException $ReflectExc) {
                throw new \InvalidArgumentException('The given, ' . $this->nullObjectType . ', could not be loaded.', null, $ReflectExc);
            }
        }
        $this->TypeValidator->throwExceptionIfObjectNotParentType($NullObject);
        return $NullObject;
    }

    /**
     * $defaultOptions MUST be passed; if your class does not have or need
     * any default options there is no need to store a blueprint.
     *
     * Note that if you pass a key that already exists in the blueprint store you
     * will override whatever default options were previously set there. Please note
     * that we are not using PHP's internal array merge but our own algorithm for
     * merging a specific blueprint with the default blueprint so you do not need
     * to worry about the type of keys used by the blueprint array, simply that the
     * array elements are in the correct order for the constructor.
     *
     * Although you can pass either a Java-style or PHP-style namespaced class
     * the key should be stored and retrieved as a PHP-style class string.
     *
     * @param string $className
     * @param array $defaultParameters
     */
    public function storeBlueprint($className, array $defaultParameters) {
        $blueprintKey = $this->JavaNameConverter->convertJavaClassToPhpClass($className);
        $this->blueprints[$blueprintKey] = $defaultParameters;
    }

    /**
     * Will always return an array, an empty one if no blueprint is associated
     *
     * @param string $className
     * @return array
     */
    public function getBlueprint($className) {
        $bluePrintKey = $this->JavaNameConverter->convertJavaClassToPhpClass($className);
        if (\array_key_exists($bluePrintKey, $this->blueprints) && \is_array($this->blueprints[$bluePrintKey])) {
            return $this->blueprints[$bluePrintKey];
        }
        return array();
    }

    /**
     * @param string $className
     * @return boolean
     */
    public function deleteBlueprint($className) {
        $bluePrintKey = $this->JavaNameConverter->convertJavaClassToPhpClass($className);
        if (\array_key_exists($bluePrintKey, $this->blueprints)) {
            unset($this->blueprints[$bluePrintKey]);
        }
        return (!isset($this->blueprints[$bluePrintKey])) ? true : false;
    }

    /**
     * If there is a problem creating the given object a clone of the NullObject
     * prototype for this factory will be returned.
     *
     * @param string $className
     * @param array $parameters
     */
    public function makeObject($className, array $parameters = array()) {
        try {
            $className = $this->JavaNameConverter->convertJavaClassToPhpClass($className);
            $parameters = $this->getFinalBlueprint($className, $parameters);
            $ReflectedClass = $this->ReflectionCache->getClass($className);
            $returnObject = $ReflectedClass->newInstanceArgs($parameters);
            $this->TypeValidator->throwExceptionIfObjectNotParentType($returnObject);
        } catch (\ReflectionException $ReflectExc) {
            $returnObject = clone $this->NullObject;
            $this->LogOverseer->logError('There was an error creating the requested object, ' . $className . '.  It likely does not exist.');

        } catch (\InvalidArgumentException $InvalArgExc) {
            $returnObject = clone $this->NullObject;
            $this->LogOverseer->logError('The requested object, ' . $className . ', does not properly implement the appropriate type, ' . $this->objectType . ', for this factory.');
        }
        return $returnObject;
    }

    /**
     * Take an array of options for a specific object, replace any NULL values
     * with those values stored in $blueprints, given that the \a $className
     * has a blueprint associated with it.
     *
     * The final blueprint is a combination of the default blueprint, if one is
     * stored for a given class, and the $options passed when creating the
     * object.  The options passed should be whatever parameters are needed for
     * the given object, in the order of the parameters in the constructor.  Meaning
     * the 0-index array element will be the first argument in the constructor, the
     * 1-index array element will be the second argument, etc.
     *
     * If an n-index array element in \a $options is null it is replaced with the
     * appropriate value in the default blueprint.  If there is no stored blueprint
     * $options will be returned with no changes.  Finally, if there are more
     * elements in $options than the default blueprint the additional elements
     * will be appended on the end, after all null elements are replaced with
     * their default values.
     *
     * @internal
     * We are not using array_merge here for a reason.  We want users to be able
     * to use any kind of index they would like for their options arrays.  If we
     * used array_merge users would have to ensure that the keys for the blueprint
     * and the options always matched up.  Ultimately this sounds really tedious
     * and not something we want to enforce upon the user.  For now we will simply
     * use the below alorithm to determine the final blueprint to be used.  If you
     * feel that this algorithm can be approved upon please raise an issue at
     * {@link http://www.github.com/cspray/issues/}
     *
     * @param string $className
     * @param array $options
     * @return array
     */
    protected function getFinalBlueprint($className, array $options) {
        $storedBlueprint = $this->getBlueprint($className);
        if (empty($storedBlueprint)) {
            return $options;
        }

        $blueprintCount = \count($storedBlueprint);
        $optionsCount = \count($options);
        $appendedOptions = null;

        if ($optionsCount !== $blueprintCount) {
            if ($optionsCount > $blueprintCount) {
                $appendedOptions = \array_splice($options, $blueprintCount);
            } else {
                $options = \array_pad($options, $blueprintCount, null);
            }
        }

        $finalBlueprint = array();

        for ($i = 0; $i < $blueprintCount; $i++) {
            $indexVal = $storedBlueprint[$i];
            if (!\is_null($options[$i])) {
                $indexVal = $options[$i];
            }
            $finalBlueprint[] = $indexVal;
        }

        if (!empty($appendedOptions)) {
            foreach ($appendedOptions as $val) {
                $finalBlueprint[] = $val;
            }
        }

        return $finalBlueprint;
    }

    /**
     * @return string
     */
    public function getObjectType() {
        return $this->objectType;
    }

    public function getNullObjectType() {
        return $this->nullObjectType;
    }

}