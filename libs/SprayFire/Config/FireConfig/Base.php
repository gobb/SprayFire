<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Config\FireConfig;

use \SprayFire\CoreObject as CoreObject;

abstract class Base extends CoreObject {

    protected $properties = array();

    protected function get($property) {
        if ($this->has($property)) {
            $value = $this->properties[$property];
            return $value;
        }
        return null;
    }

    protected function set($property, $value) {
        $this->properties[$property] = $value;
    }

    protected function has($property) {
        return \array_key_exists($property, $this->properties);
    }

}