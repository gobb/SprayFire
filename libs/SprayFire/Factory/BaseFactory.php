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
     * @property $nullPrototype The name of the class to use for NullObject on this factory
     */
    protected $nullPrototype;

    /**
     * @brief Note \a $defaultOptions MUST be passed; if your class does not have
     * or need any default options there is no need to store a blueprint.
     *
     * @details
     * Note that if you pass a key that already exists in the blueprint store you
     * will override whatever default options were previously set there.
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
     * @brief The \a $className passed will be used as the prototype when a requested
     * Object could not be created and a NullObject should be returned instead.
     *
     * @param $className A Java or PHP-styled namespaced class
     */
    public function setNullPrototype($className) {
        $this->nullPrototype = $this->replaceDotsWithBackSlashes($className);
    }

    /**
     * @param $dotSeparatedClass A Java-style namespaced class
     * @return A PHP-style namespaced class
     */
    protected function replaceDotsWithBackSlashes($dotSeparatedClass) {
        $dotSeparator = '.';
        $slashSeparator = '\\';
        $slashedClassName = \str_replace($dotSeparator, $slashSeparator, $dotSeparatedClass);
        $trimmedClassName = \trim($slashedClassName, '\\ ');
        return '\\' . $trimmedClassName;
    }

}