<?php

/**
 * @file
 * @brief Holds a class designed to store a unique set of generic SprayFire.Core.Object
 * objects.
 */

namespace SprayFire\Structure\Collection;

/**
 * @brief Ensures that the collection of objects do not have duplicate values.
 */
class GenericSet extends \SprayFire\Structure\Collection\GenericCollection {

    /**
     * @param $Object \SprayFire\Core\Object
     * @return The index for \a $Object or false if it exists in the Set
     */
    public function addObject(\SprayFire\Core\Object $Object) {
        if ($this->containsObject($Object)) {
            return false;
        }
        return parent::addObject($Object);
    }

}