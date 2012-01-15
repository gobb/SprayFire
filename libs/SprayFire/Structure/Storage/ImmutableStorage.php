<?php

/**
 * @file
 * @brief A simple key/value storage object that extends SprayFire.Core.Structure.DataStorage
 * and does not allow the data associated to be changed after the object has been
 * constructed.
 */

namespace SprayFire\Structure\Storage;

/**
 * @brief An object that allows for data to be stored and to be assured that
 * the data is not mutable.
 *
 * @details
 * This object is immutable by the fact that after the object is constructed
 * attempting to __set the object or offsetSet the object's properties will
 * results in a SprayFire.Exceptions.UnsupportedOperationException will
 * be thrown.  If a class extends this please ensure that it is a truly immutable
 * object and does not have any "backdoors".
 *
 * @uses SprayFire.Structure.Storage.DataStorage
 */
class ImmutableStorage extends \SprayFire\Structure\Storage\DataStorage {

    /**
     * @param $key string
     * @param $value mixed
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    protected function offsetSet($key, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('Attempting to set the value of an immutable object.  ' . $key . ', was not set to ' . $value );
    }

    /**
     * @param $key string
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    protected function offsetUnset($key) {
        throw new \SprayFire\Exception\UnsupportedOperationException('Attempting to remove the value of an immutable object.  ' . $key . ' was not removed.');
    }

}