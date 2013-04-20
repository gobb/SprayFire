<?php

/**
 * Implementation to normalize class and method names into something that fits
 * the SprayFire coding standards.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Routing\FireRouting;

use \SprayFire\StdLib;

/**
 * This is a package private class and is intended to be used by other implementations
 * in the SprayFire.Http.Routing.FireRouting module.
 *
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
class Normalizer extends StdLib\CoreObject {

    /**
     * PCRE regex pattern used to remove all non-alphabetic/space characters
     *
     * @property string
     */
    protected $characterRegex = '/[^A-Za-z\s]/';

    /**
     * Will replace dashes and underscores with spaces, remove anything that isn't
     * a letter and finally return a string with resulting fragments converted to
     * a pascal cased string.
     *
     * @param string $controller
     * @return string
     */
    public function normalizeController($controller) {
        $class = \strtolower($controller);
        $class = $this->replaceUnderscoresWithSpaces($class);
        $class = $this->replaceDashesWithSpaces($class);
        $class = $this->removeInvalidCharacters($class);
        return $this->makePascalCased($class);
    }

    /**
     * Will replace dashes and underscores with spaces, remove anything that isn't a
     * letter or a number and return a string with resulting fragments converted
     * to a camel cased string.
     *
     * @param string $action
     * @return string
     *
     * @todo
     * We need to make a test to normalize an action with a number in it.  This
     * is not robust enough to ship with version 0.1
     */
    public function normalizeAction($action) {
        $action = \strtolower($action);
        $action = $this->replaceUnderscoresWithSpaces($action);
        $action = $this->replaceDashesWithSpaces($action);
        $action = $this->removeInvalidCharacters($action);
        return $this->makeCamelCased($action);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function replaceUnderscoresWithSpaces($string) {
        $underscore = '_';
        return $this->replaceCharacterWithSpaces($string, $underscore);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function replaceDashesWithSpaces($string) {
        $dash = '-';
        return $this->replaceCharacterWithSpaces($string, $dash);
    }

    /**
     * @param string $string
     * @param string $character
     * @return string
     */
    protected function replaceCharacterWithSpaces($string, $character) {
        $space = ' ';
        if (\strpos($string, $character) === false) {
            return $string;
        }
        return (string) \str_replace($character, $space, $string);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function removeInvalidCharacters($string) {
        return (string) \preg_replace($this->characterRegex, '', $string);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function makePascalCased($string) {
        $class = \ucwords($string);
        $class = \str_replace(' ', '', $class);
        return (string) $class;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function makeCamelCased($string) {
        $string = $this->makePascalCased($string);
        return \lcfirst($string);
    }

}
