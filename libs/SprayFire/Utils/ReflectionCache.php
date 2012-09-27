<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Utils;

use \SprayFire\CoreObject as SFCoreObject;

class ReflectionCache extends SFCoreObject {

    protected $JavaNameConverter;

    protected $cache = array();

    public function __construct(JavaNamespaceConverter $JavaNameConverter) {
        $this->JavaNameConverter = $JavaNameConverter;
    }

    public function getClass($className) {
        $className = $this->JavaNameConverter->convertJavaClassToPhpClass($className);
        if (isset($this->cache[$className])) {
            return $this->cache[$className];
        }

        $Reflection = new \ReflectionClass($className);
        $this->cache[$className] = $Reflection;
        return $Reflection;
    }

}