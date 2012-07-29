<?php

/**
 * Will normalize controller and action names for routing purposes.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing\FireRouting;

/**
 * Will take a controller or action URI fragment and return a string that is formatted
 * to follow the rules defined at http://www.github.com/cspray/SprayFire/wiki/Routing.
 */
class Normalizer extends \SprayFire\CoreObject {

    /**
     * PCRE regex pattern used to remove all non-alphabetic/space characters
     *
     * @property string
     */
    protected $characterRegex = '/[^A-Za-z\s]/';

    /**
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
     * @param strubg $action
     * @return string
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
     * @param strubg $string
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