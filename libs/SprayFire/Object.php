<?php

/**
 * Interface to represent a generic object within the context of SprayFire or a
 * SprayFire-driven application.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire;

/**
 * @package SprayFire
 */
interface Object {

    /**
     * Should return true or false for whether or not the given $Object is equal
     * to the object invoking equals.
     *
     * @param \SprayFire\Object $Object
     * @return boolean
     */
    public function equals(Object $Object);

    /**
     * Should return some unique identifier for the object.
     *
     * @return string
     */
    public function hashCode();

    /**
     * @return string
     */
    public function __toString();

}
