<?php

/**
 * Utility object that provides the ability to convert Java style class names
 * into PHP style class names.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */

namespace SprayFire\StdLib;

/**
 * @package SprayFire
 * @subpackage StdLib
 */
interface JavaNamespaceConverter {

    /**
     * Ensure that the string returned has had all dot separators converted into
     * PHP's normal backslash namespace separator.
     *
     * @param string $class
     * @return string
     */
    public function convertJavaClassToPhpClass($class);

}
