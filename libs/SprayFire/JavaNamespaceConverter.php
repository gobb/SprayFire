<?php

/**
 * A class that allows for the conversion of Java style namespaces to PHP namespaces.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire;

use \SprayFire\CoreObject as CoreObject;

class JavaNamespaceConverter extends CoreObject {

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