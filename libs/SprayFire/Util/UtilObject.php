<?php

/**
 * @file
 * @brief This object holds a class that is used to provide some common, base
 * functionality to a wide variety of objects.
 */

namespace SprayFire\Util;

/**
 * @brief A utility SprayFire.Object; think of Batman's utility belt.
 *
 * @details
 * This is NOT a class to just add anything that you want.  Instead really think
 * about the method and whether it is appropriate to truly be considered a utility
 * that should be available to a wide variety of objects.  This functionality should
 * be generic enough that it would be considered reasonable for any object to have
 * access to it.
 */
class UtilObject extends \SprayFire\CoreObject {

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