<?php

/**
 *
 */

namespace SprayFire;

/**
 *
 */
class JavaNamespaceConverter extends \SprayFire\CoreObject {

    /**
     * @param $className A Java-style namespaced class
     * @return A PHP-style namespaced class
     */
    protected function convertJavaClassToPhpClass($className) {
        if (!\is_string($className)) {
            return $className;
        }

        $backSlash = '\\';
        $dot = '.';
        if (\strpos($className, $dot) !== false) {
            $className = \str_replace($dot, $backSlash, $className);
        }
        return $backSlash . \trim($className, '\\ ');
    }

}