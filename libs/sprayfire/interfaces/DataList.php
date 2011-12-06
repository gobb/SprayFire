<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.2.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * This interface should be implemented by all list objects, this data structure
 * should ensure that the $Object passed to it implement or extend the interface
 * or class name set by the constructor.
 *
 * Only one data type may be stored in a list at one time.  We have chose to force
 * the passing of CoreObject instead of stdClass to ensure that the objects have
 * some kind of `equals()` method to invoke.
 */
interface DataList extends Iterator {

    /**
     * Ensures that the list knows what interface or class the objects passed to it
     * should be a subclass of.
     *
     * @param string $interfaceOrClassName
     */
    public function __construct($interfaceOrClassName);

    /**
     * Should throw an exception if the list implementation does not have the proper
     * number of buckets available or the object passed is not of the correct type.
     *
     * @param CoreObject $Object
     * @return void
     * @throws InvalidArgumentException
     *         OutOfRangeException
     */
    public function add(CoreObject $Object);

    /**
     * Will return the CoreObject that is found at the index passed, if the index
     * is invalid an OutOfRangeException should be thrown.
     *
     * Should return an instance of an object that implements the parent type passed
     * in the constructor.
     *
     * @param int $index
     * @return mixed
     * @throws OutOfRangeException
     */
    public function get($index);

    /**
     * Assigns the given object to the assigned index, if the index is less than
     * or greater than the maximum bounds of the list an exception should be
     * thrown.
     *
     * This method is optional, if the list implementation does not support this
     * operation it should throw an UnsupportedOperationException.
     *
     * @param int $index
     * @param CoreObject $Object
     * @return boolean
     * @throws OutOfRangeException
     *         InvalidArgumentException
     *         UnsupportedOperationException
     *
     */
    public function set($index, CoreObject $Object);

    /**
     * Will verify that the given object exists in the list of objects, the `equals()`
     * method should be invoked on the objects stored to determine if they are
     * equal to one another.
     *
     * @param CoreObject $Object
     * @return boolean
     */
    public function contains(CoreObject $Object);

    /**
     * Will remove the given object if it exists in the list.
     *
     * This method is optional, if the list implementation does not support this
     * operation it should throw an UnsupportedOperationException.
     *
     * @param CoreObject $Object
     * @return void
     * @throws InvalidArgumentException
     *         UnsupportedOperationException
     */
    public function remove(CoreObject $Object);

    /**
     * Will return an integer value representing the index for the given object,
     * if the object could not be found -1 will be returned.
     *
     * @param CoreObject $Object
     * @return int
     */
    public function indexOf(CoreObject $Object);

    /**
     * Will return true if the given list has no object stored in it.
     *
     * @return boolean
     */
    public function isEmpty();

    /**
     * Will return the number of objects being stored in the list.
     *
     * @return int
     */
    public function size();

    /**
     * Should set the size that this list should be restricted to and return the
     * actual max size that was set.
     *
     * @param int $maxSize
     * @return int
     */
    public function setMaxSize($maxSize);

}

// End DataList
