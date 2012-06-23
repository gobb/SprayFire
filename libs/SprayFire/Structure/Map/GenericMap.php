<?php

/**
 * @file
 * @brief Provides a very basic implementation that allows for the storing of any
 * SprayFire.Core.Object passed.
 */

namespace SprayFire\Structure\Map;

/**
 * @brief Base implementation of the SprayFire.Core.Structure.ObjectMap interface,
 * it simply allows for any SprayFire.Core.CoreObject to be added.
 *
 * @uses IteratorAggregate
 * @uses InvalidArgumentException
 * @uses SprayFire.Object
 * @uses SprayFire.Structure.ObjectMap
 * @uses SprayFire.Core.Util.CoreObject
 */
class GenericMap extends \SprayFire\CoreObject implements \IteratorAggregate, \SprayFire\Structure\ObjectMap {

    /**
     * @brief Holds the objects being stored in this data structure
     *
     * @property $data
     */
    protected $data = array();

    /**
     * @param $Object SprayFire.Object to be stored in Map
     * @return True if Map stores \a $Object or false if it doesn't
     */
    public function containsObject(\SprayFire\Object $Object) {
        if ($this->getKey($Object) === false) {
            return false;
        }
        return true;
    }

    /**
     * @param $key string representing an ID associated with an object
     * @return true if map has a key with an object associated to it false if not
     */
    public function containsKey($key) {
        if (!\array_key_exists($key, $this->data) || !isset($this->data[$key])) {
            return false;
        }
        return true;
    }

    /**
     * @return An integer representing the number of objects stored
     */
    public function count() {
        return \count($this->data);
    }

    /**
     * @param $key A string associated with a given SprayFire.Core.Object stored
     * @return A SprayFire.Core.Object or null if the object does not exist
     */
    public function getObject($key) {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @brief Return the stringassociated with the given object or false if the
     * object is not stored.
     *
     * @param $Object SprayFire.Object
     * @return mixed \a $key associated with \a $Object or false on failure
     */
    public function getKey(\SprayFire\Object $Object) {
        $index = false;
        foreach ($this->data as $key => $StoredObject) {
            if ($Object->equals($StoredObject)) {
                $index = $key;
                break;
            }
        }
        return $index;
    }

    /**
     * @return true if the Map has no elements and false if it does
     */
    public function isEmpty() {
        return \count($this) <= 0;
    }

    /**
     * @param $key A string associated with a key
     */
    public function removeKey($key) {
        if (\array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }
    }

    /**
     * @param $key string
     * @param $Object SprayFire.Object to store in the Map
     * @throws InvalidArgumentException
     */
    public function setObject($key, \SprayFire\Object $Object) {
        $this->throwExceptionIfKeyInvalid($key);
        $this->data[$key] = $Object;
    }

    /**
     * @brief Satisfies the requirements of the IteratorAggregate interface
     *
     * @return ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    /**
     * @param $key string
     * @throws InvalidArgumentException
     */
    protected function throwExceptionIfKeyInvalid($key) {
        if (empty($key) || !\is_string($key)) {
            throw new \InvalidArgumentException('The key for an object may not be an empty or non-string value.');
        }
    }

}