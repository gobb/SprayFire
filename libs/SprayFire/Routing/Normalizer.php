<?php

/**
 * Will normalize controller and action names for routing purposes.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing;

/**
 * Will take a controller or action URI fragment and return a string that is formatted
 * to follow the rules defined at http://www.github.com/cspray/SprayFire/wiki/Routing.
 */
class Normalizer extends \SprayFire\Util\CoreObject {

    /**
     * PCRE regex pattern used to remove all non-alphabetic/space characters
     *
     * @property string
     */
    protected $characterRegex = '/[^A-Za-z\s]/';

    /**
     * @param $controller string Controller fragment from URL
     * @return string Normalized controller class name
     * @see http://www.github.com/cspray/SprayFire/wiki/Routing
     */
    public function normalizeController($controller) {
        $class = \strtolower($controller);
        $class = $this->replaceUnderscoresWithSpaces($class);
        $class = $this->replaceDashesWithSpaces($class);
        $class = $this->removeInvalidCharacters($class);
        return $this->makePascalCased($class);
    }

    /**
     * @param $action string Name of an action on a controller
     * @return string Normalized action name
     * @see http://www.github.com/cspray/SprayFire/wiki/Routing
     */
    public function normalizeAction($action) {
        $action = \strtolower($action);
        $action = $this->replaceUnderscoresWithSpaces($action);
        $action = $this->replaceDashesWithSpaces($action);
        $action = $this->removeInvalidCharacters($action);
        return $this->makeCamelCased($action);
    }

    /**
     * @param $string string To replace all underscores
     * @return string A string with underscores turned into spaces
     */
    protected function replaceUnderscoresWithSpaces($string) {
        $underscore = '_';
        return $this->replaceCharacterWithSpaces($string, $underscore);
    }

    /**
     * @param $string string To replace all dashes
     * @return string A string with dashes turned into spaces
     */
    protected function replaceDashesWithSpaces($string) {
        $dash = '-';
        return $this->replaceCharacterWithSpaces($string, $dash);
    }

    /**
     * @param $string string The string to replace characters with spaces
     * @param $character string Character to replace with spaces
     * @return string A string with all \a $character turned into spaces
     */
    protected function replaceCharacterWithSpaces($string, $character) {
        $space = ' ';
        if (\strpos($string, $character) === false) {
            return $string;
        }
        return \str_replace($character, $space, $string);
    }

    /**
     * @param $string string A string that should have all non-alphabetic/space characters removed
     * @return string A string with no invalid characters
     */
    protected function removeInvalidCharacters($string) {
        return \preg_replace($this->characterRegex, '', $string);
    }

    /**
     * @param $string string A lowercase, space separated string
     * @return string A string with the appropriate PascalCase and all spaces removed
     */
    protected function makePascalCased($string) {
        $class = \ucwords($string);
        $class = \str_replace(' ', '', $class);
        return $class;
    }

    /**
     * @param $string string A lowercase, space separated string
     * @return string A string with the appropriate camelCase and all spaces removed
     */
    protected function makeCamelCased($string) {
        $string = $this->makePascalCased($string);
        return \lcfirst($string);
    }



}