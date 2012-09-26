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