<?php

/**
 * @file
 * @brief An interface that should be implemented by data structures holding
 * SprayFire objects.
 */

namespace SprayFire\Structure;

/**
 * @brief Provides an API to store SprayFire derived objects, iterate over them
 * and store similar typed objects.
 *
 * @details
 * Please note that this data structure will only store classes implementing
 * the SprayFire.Core.Object interface.  If this interface is not implemented
 * by the objects being added an InvalidArgumentException should be thrown to
 * let the calling code know that the object is of the incorrect type.
 *
 * The only way this data structure should be manipulated and interacted with
 * is through the supplied interface; do not implement this interface through
 * the SprayFire.Datastructs.MutableStorage or SprayFire.Datastructs.ImmutableStorage
 * objects as they allow for the storing of any data type through the SprayFire.Datastructs.Overloadable
 * and ArrayAccess interfaces.
 *
 * @uses Traversable
 * @uses Countable
 * @uses SprayFire.Object
 */
interface ObjectMap extends \Traversable, \Countable {

    /**
     * @brief Assigns the passed \a $Object to the given \a $key if
     * the key exists the value it stores will be overwritten by the new
     * \a $Object.
     *
     * @details
     * If \a $Object does not implement the proper type passed in the class
     * constructor this method should throw an InvalidArgumentException and if
     * the object storage being implemented does not allow for the setting of
     * new or existing objects this method should throw a
     * SprayFire.Exceptions.UnsupportedOperationException.
     *
     * @param $key string
     * @param $Object SprayFire.Object
     * @return void
     * @throws SprayFire.Exceptions.UnsupportedOperationException
     * @throws InvalidArgumentException
     */
    public function setObject($key, \SprayFire\Object $Object);

    /**
     * @brief Return an Object if one exists for the given key or null.
     *
     * @param $key string
     * @return SprayFire.Object
     */
    public function getObject($key);

    /**
     * @brief Return the index for \a $Object or false if the object does
     * not exist in the storage.
     *
     * @details
     * The value returned from this method is likely to be a string as compared
     * to a numeric index; ultimately however it will return whatever index
     * value was set for the \a $Object.
     *
     * @param $Object SprayFire.Object
     * @return mixed
     */
    public function getKey(\SprayFire\Object $Object);

    /**
     * @brief Returns a boolean value indicating whether the \a $Object is
     * stored.
     *
     * @details
     * SprayFire.Core.Object::equals() method will be used to determine
     * if the passed \a $Object is contained within this storage.
     *
     * @param $Object SprayFire.Object
     * @return boolean true if \a $Object is stored; false if it isn't
     */
    public function containsObject(\SprayFire\Object $Object);

    /**
     * @brief Returns a boolean value indicating whether the \a $key exists and
     * has an object associated with it.
     *
     * @param $key The string ID for the object to check
     * @return boolean true if \a $key has an object associated with it; false if it doesn't
     */
    public function containsKey($key);

    /**
     * @brief Remove the object associated with \a $key, there is no need
     * to return a value.
     *
     * @param $key string
     * @return void
     */
    public function removeKey($key);

    /**
     * @return boolean
     */
    public function isEmpty();

}