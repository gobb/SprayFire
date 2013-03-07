<?php

/**
 * A trait that satisfies the requirements for the StdLib\JavaNamespaceConverter
 * interface.
 *
 * @author Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */

namespace SprayFire\StdLib;

/**
 * @package SprayFire
 * @subpackage StdLib
 */
trait JavaConverterTrait {

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
