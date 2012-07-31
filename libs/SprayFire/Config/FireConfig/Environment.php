<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Config\FireConfig;

use \SprayFire\Config\Environment as ConfigEnvironment,
    \SprayFire\Config\FireConfig\Base as FireConfigBase;

class Environment extends FireConfigBase implements ConfigEnvironment {

    protected $properties = array();

    public function getProperty($property) {
        $this->get($property);
    }

    public function hasProperty($property) {
        $this->has($property);
    }

    public function setProperty($property, $value) {
        $this->set($property, $value);
    }

}