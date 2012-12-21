<?php

/**
 * Utility object used to ensure that needless Reflection objects are not
 * created when dynamically creating objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Utils;

use \SprayFire\CoreObject as SFCoreObject,
    \ReflectionClass as ReflectionClass;

/**
 * @package SprayFire
 * @subpackage Utils
 */
class ReflectionCache extends SFCoreObject {

    /**
     * Used to ensure that we can handle creating Reflection objects with both
     * Java and PHP style class names.
     *
     * @property SprayFire.Utils.JavaNamespaceConverter
     */
    protected $JavaNameConverter;

    /**
     * Key value storing [$className => Reflection] for each Reflection object
     * created.
     *
     * @property array
     */
    protected $cache = array();

    /**
     * @param \SprayFire\Utils\JavaNamespaceConverter $JavaNameConverter
     */
    public function __construct(JavaNamespaceConverter $JavaNameConverter) {
        $this->JavaNameConverter = $JavaNameConverter;
    }

    /**
     * Will return a Reflection of the given $className or throw an exception
     * if there was an error.
     *
     * @param string $className
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function getClass($className) {
        $className = $this->JavaNameConverter->convertJavaClassToPhpClass($className);
        if (isset($this->cache[$className])) {
            return $this->cache[$className];
        }

        $Reflection = new ReflectionClass($className);
        $this->cache[$className] = $Reflection;
        return $Reflection;
    }

}
