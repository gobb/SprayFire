<?php

/**
 * Implementation of SprayFire.Core.Structure.ObjectCollection that allows
 * for any SprayFire.Core.Object to be stored inside.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Structure\Collection;

/**
 * Allows for the storage of a series of SprayFire.Core.Object instances.
 *
 * Allows the storing and retrieval of a series of SprayFire.Core.Object instances.
 * These objects are stored with a numeric indexed.  In addition this collection guarantees
 * that the object stored for a given index will always remain the same or that index
 * will hold an empty value.  Meaning, if an object is added and given the index of
 * '1', no other object will ever be given that index.  If the object is removed
 * and the '1' index is retrieved a null value will be returned.  No method is provided
 * to the user to then set the '1' index to a different object instance.
 *
 * @uses IteratorAggregate
 * @uses SplFixedArray
 * @uses SprayFire.Structure.ObjectCollection
 * @uses SprayFire.Core.Util.CoreObject
 */
class GenericCollection extends \SprayFire\Util\CoreObject implements \IteratorAggregate, \SprayFire\Structure\ObjectCollection {

    /**
     * @property SplFixedArray
     */
    protected $data;

    /**
     * Next index to use when addObject() is called
     *
     * @property integer
     */
    protected $index = 0;

    /**
     * @param $initialSize integer Number of buckets to originally have in collection
     */
    public function __construct($initialSize = 32) {
        $this->data = new \SplFixedArray($initialSize);
    }

    /**
     * Adds an object to the collection, assigning the current index.
     *
     * @param $Object SprayFire.Object An object to add to the collection
     * @return boolean The index of \a $Object or false if \a $Object was not added
     */
    public function addObject(\SprayFire\Object $Object) {
        if ($this->index === $this->data->count()) {
            $this->doubleSizeOfCollection();
        }
        $objectIndex = $this->index;
        $this->data[$objectIndex] = $Object;
        $this->index++;
        return $objectIndex;
    }

    protected function doubleSizeOfCollection() {
        $newSize = $this->data->count() * 2;
        $this->data->setSize($newSize);
    }

    /**
     * @param $Object SprayFire.Object
     * @return boolean true if the \a $Object exists anywhere in the collection
     */
    public function containsObject(\SprayFire\Object $Object) {
        foreach ($this->data as $value) {
            if (isset($value)) {
                if ($Object->equals($value)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Return the index of the first element in the collection equal to \a $Object.
     *
     * @param $Object SprayFire.Object
     * @return mixed integer if \a $Object is found, false if it is not found
     */
    public function getIndex(\SprayFire\Object $Object) {
        foreach ($this->data as $key => $value) {
            if (isset($value)) {
                if ($Object->equals($value)) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * @param $index An integer representing index for the object to retrieve
     * @return SprayFire.Core.Object or null if no value set for the index
     */
    public function getObject($index) {
        $this->throwExceptionIfIndexInvalid($index);
        return $this->data[$index];
    }

    /**
     * @param $index integer Representing a valid index to remove
     * @return void
     * @throws InvalidArgumentException
     */
    public function removeIndex($index) {
        $this->throwExceptionIfIndexInvalid($index);
        unset($this->data[$index]);
    }

    /**
     * @param $index integer Index to check validity of
     * @return void
     * @throws InvalidArgumentException
     */
    protected function throwExceptionIfIndexInvalid($index) {
        if (!is_int($index) || $index < 0 || $index >= $this->data->count()) {
            throw new \InvalidArgumentException('The index passed is not valid.  Either a string was passed or an integer value outside of the range of the collection.');
        }
    }

    /**
     * @param $Object SprayFire.Object
     * @return void
     */
    public function removeObject(\SprayFire\Object $Object) {
        $index = $this->getIndex($Object);
        if ($index !== false) {
            unset($this->data[$index]);
        }
    }

    /**
     * @brief Please be aware that this iterator will allow you to traverse over
     * every *bucket* in the collection and is not restricted to only object elements.
     *
     * @return Iterator
     */
    public function getIterator() {
        return $this->data;
    }

    /**
     * @return integer Actual number of objects stored
     */
    public function count() {
        $count = 0;
        foreach ($this->data as $value) {
            if (isset($value)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return integer
     */
    public function getNumberOfBuckets() {
        return $this->data->count();
    }

    /**
     * @return boolean
     */
    public function isEmpty() {
        return \count($this) === 0;
    }

}