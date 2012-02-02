<?php

/**
 * @file
 * @brief Holds a class that will normalize controller and action names for routing
 * purposes.
 */

namespace SprayFire\Routing;

/**
 * @brief This class will take a controller or action URI fragment and return a
 * string that is formatted to follow the rules defined at http://www.github.com/cspray/SprayFire/wiki/Routing.
 */
class Normalizer extends \SprayFire\Util\CoreObject {

    /**
     * @brief A PCRE regex pattern used to remove all non-alphabetic/space characters
     *
     * @var $characterRegex string
     */
    protected $characterRegex = '/[^A-Za-z\s]/';

    /**
     * @param $controller String representing controller fragment from URL
     * @return Normalized controller class name
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
     * @param $action String representing the name of an action on a controller
     * @return Normalized action name
     * @see http://www.github.com/cspray/SprayFire/wiki/Routing
     */
    public function normalizeAction($action) {

    }

    /**
     * @param $string A string to replace all underscores in
     * @return A string with underscores turned into spaces
     */
    protected function replaceUnderscoresWithSpaces($string) {
        $underscore = '_';
        return $this->replaceCharacterWithSpaces($string, $underscore);
    }

    /**
     * @param $string A string to replace all dashes in
     * @return A string with dashes turned into spaces
     */
    protected function replaceDashesWithSpaces($string) {
        $dash = '-';
        return $this->replaceCharacterWithSpaces($string, $dash);
    }

    /**
     * @param $string The string to replace characters with spaces
     * @param $character A string character to replace with spaces
     * @return A string with all \a $character turned into spaces
     */
    protected function replaceCharacterWithSpaces($string, $character) {
        $space = ' ';
        if (\strpos($string, $character) === false) {
            return $string;
        }
        return \str_replace($character, $space, $string);
    }

    /**
     * @param $string A string that should have all non-alphabetic/space characters removed
     * @return A string with no invalid characters
     */
    protected function removeInvalidCharacters($string) {
        return \preg_replace($this->characterRegex, '', $string);
    }

    /**
     * @param $string A lowercase, space separated string
     * @return A string with the appropriate PascalCase and all spaces removed
     */
    protected function makePascalCased($string) {
        $class = \ucwords($string);
        $class = \str_replace(' ', '', $class);
        return $class;
    }



}