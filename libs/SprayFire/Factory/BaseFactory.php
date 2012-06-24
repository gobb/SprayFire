<?php

/**
 * An abstract class that provides some common functionality and
 * groundwork for concrete factory implementations.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Factory;

/**
 * @brief Will provide a variety of utility functions that factory implementations
 * may find useful; all SprayFire provided factories will extend this class.
 *
 * @uses ReflectionClass
 * @uses InvalidArgumentException
 * @uses SprayFire.Factory.Factory
 * @uses SprayFire.Util.CoreObject
 * @uses SprayFire.Util.ObjectTypeValidator
 * @uses SprayFire.Exception.TypeNotFoundException
 * @uses Artax.ReflectionCacher
 */
abstract class BaseFactory extends \SprayFire\JavaNamespaceConverter implements \SprayFire\Factory\Factory {

    /**
     * @internal Keys stored in this array should be a PHP-style namespaced class.
     *
     * @property $blueprints An array of default options for a given class
     */
    protected $blueprints = array();

    /**
     * Cache to help prevent unneeded ReflectionClasses from being created.
     *
     * @property Artax\ReflectionCacher
     */
    protected $ReflectionCache;

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
     * The \a $returnTypeRestriction and \a $nullObject may be passed as either a
     * Java or PHP-style namespaced class.
     *
     * @param $ReflectionCache Artax.ReflectionCacher implementation
     * @param $returnTypeRestriction A string class or interface name that objects
     *        of this factory must implement.
     * @param $nullObject An object or classname to use as the NullObject returned
     *        if there was an error creating the requested object.
     * @throws InvalidArgumentException Thrown if \a $nullPrototype does not implement
     *         the \a $returnTypeRestriction
     * @throws SprayFire.Exception.TypeNotFoundException Thrown if the \a $returnTypeRestriction
     *         could not properly be loaded.
     */
    public function __construct(\Artax\ReflectionPool $ReflectionCache, $returnTypeRestriction, $nullObject) {
        $this->ReflectionCache = $ReflectionCache;
        $this->objectType = $this->convertJavaClassToPhpClass($returnTypeRestriction);
        $this->nullObjectType = $this->convertJavaClassToPhpClass($nullObject);
        $this->TypeValidator = $this->createTypeValidator();
        $this->NullObject = $this->createNullObject();
    }

    /**
     * @return SprayFire.Core.Util.ObjectTypeValidator
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    protected function createTypeValidator() {
        try {
            $ReflectedType = $this->ReflectionCache->getClass($this->objectType);
            $TypeValidator = new \SprayFire\ObjectTypeValidator($ReflectedType);
            return $TypeValidator;
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The injected interface or class, ' . $this->objectType . ', could not be found.', null, $ReflectExc);
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
     * \a $defaultOptions MUST be passed; if your class does not have
     * or need any default options there is no need to store a blueprint.
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
     * @param $className string Java-style or PHP-style namespaced class
     * @param $defaultParameters array Default options for this class
     */
    public function storeBlueprint($className, array $defaultParameters) {
        $blueprintKey = $this->convertJavaClassToPhpClass($className);
        $this->blueprints[$blueprintKey] = $defaultParameters;
    }

    /**
     * @param $className string A Java-style or PHP-style namespaced class
     * @return array Blueprint if the key has one associated or an empty array
     * if no blueprint exists.
     */
    public function getBlueprint($className) {
        $bluePrintKey = $this->convertJavaClassToPhpClass($className);
        if (\array_key_exists($bluePrintKey, $this->blueprints) && \is_array($this->blueprints[$bluePrintKey])) {
            return $this->blueprints[$bluePrintKey];
        }
        return array();
    }

    /**
     * @param $className string Java-style or PHP-style namespaced class associated
     *        with a blueprint
     * @return True if the key no longer exists in the array or false on some error
     */
    public function deleteBlueprint($className) {
        $bluePrintKey = $this->convertJavaClassToPhpClass($className);
        if (\array_key_exists($bluePrintKey, $this->blueprints)) {
            unset($this->blueprints[$bluePrintKey]);
        }
        return (!isset($this->blueprints[$bluePrintKey])) ? true : false;
    }

    /**
     * If there is a problem creating the given object a clone of the NullObject
     * prototype for this factory will be returned.
     *
     * @param $className string A Java-stye or PHP-style namespaced class
     * @param $parameters array Parameters to be used for constructor of object
     */
    public function makeObject($className, array $parameters = array()) {
        try {
            $className = $this->convertJavaClassToPhpClass($className);
            $parameters = $this->getFinalBlueprint($className, $parameters);
            $ReflectedClass = $this->ReflectionCache->getClass($className);
            $returnObject = $ReflectedClass->newInstanceArgs($parameters);
            $this->TypeValidator->throwExceptionIfObjectNotParentType($returnObject);
        } catch (\ReflectionException $ReflectExc) {
            $returnObject = clone $this->NullObject;
        } catch (\InvalidArgumentException $InvalArgExc) {
            $returnObject = clone $this->NullObject;
        }
        return $returnObject;
    }

    /**
     * Take an array of options for a specific object, replace any NULL values
     * with those values stored in \a $blueprints, given that the \a $className
     * has a blueprint associated with it.
     *
     * The final blueprint is a combination of the default blueprint, if one is
     * stored for a given class, and the \a $options passed when creating the
     * object.  The options passed should be whatever parameters are needed for
     * the given object, in the order of the parameters in the constructor.  Meaning
     * the 0-index array element will be the first argument in the constructor, the
     * 1-index array element will be the second argument, etc.
     *
     * If an n-index array element in \a $options is null it is replaced with the
     * appropriate value in the default blueprint.  If there is no stored blueprint
     * \a $options will be returned with no changes.  Finally, if there are more
     * elements in \a $options than the default blueprint the additional elements
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
     * @param $className string The PHP-style namespaced class
     * @param $options array An array of constructor arguments
     * @return An array with null elements in \a $options replaced with default
     *         blueprint values if they exist.
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
     * @return The namespaced class
     */
    public function getObjectType() {
        return $this->objectType;
    }

}