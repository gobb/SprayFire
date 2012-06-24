<?php

/**
 * An interface to allow an object to define how it is converted to an array.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Structure;

interface Arrayable {

    /**
     * Should return an array representation of the object that calls the method.
     *
     * @return array
     */
    public function toArray();

}