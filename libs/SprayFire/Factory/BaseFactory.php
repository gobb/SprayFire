<?php

/**
 * @file
 * @brief Holds an abstract class that provides some common functionality and
 * groundwork for concrete factory implementations.
 */

namespace SprayFire\Factory;

/**
 * @brief Will provide a variety of utility functions that factory implementations
 * may find useful; all SprayFire provided factories will extend this class.
 *
 * @details
 * Some of the functionality provided includes:
 *
 * - storeBlueprint()
 *      Allows you to set the default options that should be passed to a particular
 *      class name.  This should allow you to be certain of the exact state of an
 *      object created by a factory.  The default options stored as a blueprint
 *      should be merged with the options passed in `Factory::makeObject()`
 * - setNullPrototype()
 *      Allows you to determine what is considered a NullObject for a given factory.
 * - replaceDotsWithBackSlashes()
 *      This is a convenience method to allow the passing of Java style "package"
 *      class names as if they were PHP namespace class names.  As the method name
 *      implies it will replace any '.' with '\\'
 */
abstract class BaseFactory extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Factory\Factory {

    /**
     * @internal Keys stored in this array should be a PHP-style namespaced class.
     *
     * @property $blueprints An array of default options for a given class
     */
    protected $blueprints = array();

    /**
     * @property $ReflectedNullPrototype The name of the class to use for NullObject on this factory
     */
    protected $NullObject;

    public function __construct($nullPrototype) {
        $NullObject = $nullPrototype;
        if (!is_object($NullObject)) {
            try {
                $ReflectedNullPrototype = new \ReflectionClass($nullPrototype);
                $NullObject = $ReflectedNullPrototype->newInstance();
            } catch (\ReflectionException $ReflectExc) {
                throw new \SprayFire\Exception\FactoryConstructionException('The given NullObject prototype, ' . $nullPrototype . ', could not be loaded.', null, $ReflectExc);
            }
        }
        $this->NullObject = $NullObject;
    }

    /**
     * @brief Note \a $defaultOptions MUST be passed; if your class does not have
     * or need any default options there is no need to store a blueprint.
     *
     * @details
     * Note that if you pass a key that already exists in the blueprint store you
     * will override whatever default options were previously set there.  Due to
     * the way that array_merge works it is *HIGHLY RECOMMENDED* that you use a
     * string key, regardless of whether that string is really a number.  Using a
     * numeric key for options WILL result in far too many and/or the incorrect
     * parameters being passed to object constructors.
     *
     * @internal
     * Although you can pass either a Java-style or PHP-style namespaced class
     * the key should be stored and retrieved as a PHP-style class string.
     *
     * @param $className A Java-style or PHP-style namespaced class
     * @param $defaultOptions The default options for this class
     */
    public function storeBlueprint($className, array $defaultOptions) {
        $blueprintKey = $this->replaceDotsWithBackSlashes($className);
        $this->blueprints[$blueprintKey] = $defaultOptions;
    }

    /**
     * @param $className A Java-style or PHP-style namespaced class
     * @return An array storing a blueprint if the key has one associated or an
     *         empty array if no blueprint exists.
     */
    public function getBlueprint($className) {
        $bluePrintKey = $this->replaceDotsWithBackSlashes($className);
        if (\array_key_exists($bluePrintKey, $this->blueprints) && \is_array($this->blueprints[$bluePrintKey])) {
            return $this->blueprints[$bluePrintKey];
        }
        return array();
    }

    /**
     * @brief
     *
     * @param $className A Java-stye or PHP-style namespaced class
     * @param $options An array of options to be used when creating this object.
     */
    protected function createObject($className, array $options) {
        try {
            $className = $this->replaceDotsWithBackSlashes($className);
            $options = $this->getFinalBlueprint($className, $options);
            $ReflectedClass = new \ReflectionClass($className);
            $returnObject = $ReflectedClass->newInstanceArgs($options);
        } catch (\ReflectionException $ReflectExc) {
            $returnObject = clone $this->NullObject;
        }
        return $returnObject;
    }

    /**
     * @brief This method will take an array of options for a specific object,
     * replace any NULL values with those values stored in \a $blueprints, given
     * that the \a $className has a blueprint associated with it.
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
     * @param $className The PHP-style namespaced class
     * @param array $options
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

        $i = 0;
        $finalBlueprint = array();

        for ($i; $i < $blueprintCount; $i++) {
            $blueprintVal = $storedBlueprint[$i];
            $optionVal = $options[$i];
            $indexVal = $blueprintVal;
            if (!is_null($optionVal)) {
                $indexVal = $optionVal;
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
     * @param $dotSeparatedClass A Java-style namespaced class
     * @return A PHP-style namespaced class
     */
    protected function replaceDotsWithBackSlashes($className) {
        $backSlash = '\\';
        $dot = '.';
        if (\strpos($className, $dot) !== false) {
            $className = \str_replace($dot, $backSlash, $dotSeparatedClass);
        }
        return $backSlash . \trim($className, '\\ ');
    }

}