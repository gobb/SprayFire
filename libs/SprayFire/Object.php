<?php

/**
 * Interface representing the root parent for all SprayFire objects.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire;

interface Object {

    /**
     * @param SprayFire.Object $Object
     * @return boolean
     */
    public function equals(\SprayFire\Object $Object);

    /**
     * @return string
     */
    public function hashCode();

    /**
     * @return string
     */
    public function __toString();

}