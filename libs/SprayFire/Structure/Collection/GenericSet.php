<?php

/**
 * Class designed to store a unique set of generic SprayFire.Core.Object
 * objects.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Structure\Collection;

/**
 * Ensures that the collection of objects do not have duplicate values.
 *
 * @uses SprayFire.Object
 * @uses SprayFire.Structure.Collection.GenericCollection
 */
class GenericSet extends \SprayFire\Structure\Collection\GenericCollection {

    /**
     * @param $Object SprayFire.Object
     * @return mixed The index for \a $Object or false if it does not exist in the Set
     */
    public function addObject(\SprayFire\Object $Object) {
        if ($this->containsObject($Object)) {
            return false;
        }
        return parent::addObject($Object);
    }

}