<?php

/**
 * Utility object used to ensure that needless Reflection objects are not
 * created when dynamically creating objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\StdLib;

use \ReflectionClass as ReflectionClass;

/**
 * @package SprayFire
 * @subpackage StdLib
 */
class ReflectionCache extends CoreObject implements JavaNamespaceConverter {

    use JavaConverterTrait;

    /**
     * Key value storing [$className => Reflection] for each Reflection object
     * created.
     *
     * @property array
     */
    protected $cache = [];

    /**
     * Will return a Reflection of the given $className or throw an exception
     * if there was an error.
     *
     * @param string $className
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function getClass($className) {
        $className = $this->convertJavaClassToPhpClass($className);
        if (isset($this->cache[$className])) {
            return $this->cache[$className];
        }

        $Reflection = new ReflectionClass($className);
        $this->cache[$className] = $Reflection;
        return $Reflection;
    }

}
