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
     * @param $controller String representing controller fragment from URL
     * @return Normalized controller class name
     * @see http://www.github.com/cspray/SprayFire/wiki/Routing
     */
    public function normalizeController($controller) {
        $class = \strtolower($controller);
        $class = \str_replace('_', ' ', $class);
        $class = \ucwords($class);
        $class = \str_replace(' ', '', $class);
        return $class;
    }

    /**
     * @param $action String representing the name of an action on a controller
     * @return Normalized action name
     * @see http://www.github.com/cspray/SprayFire/wiki/Routing
     */
    public function normalizeAction($action) {

    }

}