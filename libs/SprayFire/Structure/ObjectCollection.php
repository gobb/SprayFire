<?php

/**
 * @file
 * @brief A file holding an interface to hold a numerically index collection of objects.
 */

namespace SprayFire\Structure;

/**
 * @brief Represents the interface for a data structure that holds a numeric-indexed
 * collection of objects.
 *
 * @details
 * Please note that there should be 2 ways to count a Collection.  The first way,
 * returned when you \count($Collection) or invoke $Collection->count(), should
 * return the actual number of objects stored in the collection.  The second way,
 * which is returned by $Collection->getNumberOfBuckets() should return the
 * available number of buckets for this collection.
 *
 * @uses Countable
 * @uses Traversable
 * @uses SprayFire.Object
 */
interface ObjectCollection extends \Countable, \Traversable {

    /**
     * @param $Object SprayFire.Object to add to the collection
     * @return The integer representing the index for \a $Object or false if object not added
     * @throws InvalidArgumentException if the object type for the collection should be restricted
     *         and the object type passed does not match the restricted type
     */
    public function addObject(\SprayFire\Object $Object);

    /**
     * @param $Object SprayFire.Object object to test if the collection contains
     * @return true if the object is contained, false if it is not
     */
    public function containsObject(\SprayFire\Object $Object);

    /**
     * @param $index An unsigned integer representing the object index to remove
     * @throws InvalidArgumentException If the index is not an integer or valid index for the collection
     */
    public function removeIndex($index);

    /**
     * @brief This method should remove **all** copies of the passed $Object from
     * the collection.
     *
     * @param $Object SprayFire.Object object to remove all copies of
     */
    public function removeObject(\SprayFire\Core\Object $Object);

    /**
     * @param $index An unsigned integer representing the object index to
     *        retrieve
     * @return SprayFire.Core.Object if the $index is found, null if the $index is not stored
     * @throws InvalidArgumentException thrown if the index is a string or negative number
     */
    public function getObject($index);

    /**
     * @param $Object SprayFire.Object
     * @return Numeric index of the object or false if the object does not exist in collection
     */
    public function getIndex(\SprayFire\Object $Object);

    /**
     * @return An integer representing number of buckets, not necessarily the number
     *         of stored objects in the collection.
     */
    public function getNumberOfBuckets();

    /**
     * @return true if the collection has no members, false if it does
     */
    public function isEmpty();

}