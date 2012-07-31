<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Config;

interface Environment {

    public function getProperty($property);

    public function hasProperty($property);

    public function setProperty($property, $value);

}