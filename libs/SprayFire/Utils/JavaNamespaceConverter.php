<?php

/**
 * Utility object that provides the ability to convert Java style class names
 * into PHP style class names.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Utils;

/**
 * @package SprayFire
 * @subpackage Utils
 */
class JavaNamespaceConverter {

    /**
     * Will convert a dot separated, Java style $className into its PHP equivalent
     * with the appropriate namespace separator.
     *
     * @param string $className
     * @return string
     */
    public function convertJavaClassToPhpClass($className) {
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