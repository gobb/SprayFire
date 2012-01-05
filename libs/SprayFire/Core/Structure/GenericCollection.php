<?php

/**
 * @file
 * @brief An implementation of SprayFire.Core.Structure.ObjectCollection that allows
 * for any SprayFire.Core.Object to be stored inside.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */
namespace SprayFire\Core\Structure;

/**
 * @brief A data structure that allows for the storage of a series of SprayFire.Core.Object
 * instances.
 *
 * @details
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
 * @uses SprayFire.Core.Structure.ObjectCollection
 */
class GenericCollection extends \SprayFire\Core\CoreObject implements \IteratorAggregate, \SprayFire\Core\Structure\ObjectCollection {

    /**
     * @brief An SplFixedArray used to store the elements of the collection
     *
     * @property $data
     */
    protected $data;

    /**
     * @brief An integer representing the next index to use when addObject() is called
     *
     * @property $index
     */
    protected $index = 0;

    /**
     * @param $initialSize The number of buckets to originally have in collection
     */
    public function __construct($initialSize = 32) {
        $this->data = new \SplFixedArray($initialSize);
    }

    /**
     * @brief Adds an object to the collection, assigning the current index.
     *
     * @param $Object \SprayFire\Core\Object An object to add to the collection
     */
    public function addObject(\SprayFire\Core\Object $Object) {
        if ($this->index === $this->data->count()) {
            $this->doubleSizeOfCollection();
        }
        $this->data[$this->index] = $Object;
        $this->index++;
    }

    protected function doubleSizeOfCollection() {
        $newSize = $this->data->count() * 2;
        $this->data->setSize($newSize);
    }

    /**
     * @param $Object \SprayFire\Core\Object
     * @return true if the \a $Object exists anywhere in the collection
     */
    public function containsObject(\SprayFire\Core\Object $Object) {
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
     * @brief Will return the index of the first element in the collection equal
     * to \a $Object.
     *
     * @param $Object SprayFire.Core.Object
     */
    public function getIndex(\SprayFire\Core\Object $Object) {
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
     * @param type $index A numeric integer representing a valid index to remove
     * @throws InvalidArgumentException
     */
    public function removeIndex($index) {
        $this->throwExceptionIfIndexInvalid($index);
        unset($this->data[$index]);
    }

    /**
     * @param $index Numeric index to check validity of
     * @throws InvalidArgumentException
     */
    protected function throwExceptionIfIndexInvalid($index) {
        if (!is_int($index) || $index < 0 || $index >= $this->data->count()) {
            throw new \InvalidArgumentException('The index passed is not valid.  Either a string was passed or an integer value outside of the range of the collection.');
        }
    }

    /**
     * @param $Object \SprayFire\Core\Object
     */
    public function removeObject(\SprayFire\Core\Object $Object) {
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
     * @return The actual number of SprayFire.Core.Object stored in the collection
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
     * @return The number of total buckets for this collection.
     */
    public function getNumberOfBuckets() {
        return $this->data->count();
    }

    /**
     * @return true if the collection has no members and false if it does
     */
    public function isEmpty() {
        return \count($this) === 0;
    }

}