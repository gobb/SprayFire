<?php

/**
 * Implementation of \SprayFire\Session\Storage provided by default framework
 * installation.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Session\FireSession;

use \SprayFire\Session as SFSession,
    \SprayFire\CoreObject as SFCoreObject,
    \IteratorAggregate as IteratorAggregate;

/**
 * @package SprayFire
 * @subpackage Session.FireSession
 */
class Storage extends SFCoreObject implements IteratorAggregate, SFSession\Storage {

    /**
     * Determine whether a key identified by $offset exists in the storage or not.
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {

    }

    /**
     * Will return the value stored against $offset or null if no value exists.
     *
     * @return mixed
     */
    public function offsetGet($offset) {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Will set a $value against
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value) {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Will remove a value stored against $offset causing it to return null if
     * retrieved without resetting.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset) {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Returns the number of keys stored for this session.
     *
     * @return integer
     */
    public function count() {
        // TODO: Implement count() method.
    }

    /**
     * Clear all data out of the session storage.
     *
     * @return void
     */
    public function clear() {
        // TODO: Implement clear() method.
    }

    /**
     * Clear data associated to a specific $key from the session storage.
     *
     * @param string $key
     * @return void
     */
    public function clearKey($key) {
        // TODO: Implement clearKey() method.
    }

    /**
     * Determine whether or not the session storage can be written to.
     *
     * @return boolean
     */
    public function isImmutable() {
        // TODO: Implement isImmutable() method.
    }

    /**
     * Will make the session storage immutable, causing an exception to be thrown
     * if the session is written to after this method is invoked.
     *
     * @return void
     */
    public function makeImmutable() {
        // TODO: Implement makeImmutable() method.
    }

    /**
     * Return a serialized string of the data for this object
     *
     * @return string
     */
    public function serialize() {

    }

    /**
     * Set the data for the object from serialized string $data.
     *
     * @param string $data
     * @return void
     */
    public function unserialize($data) {

    }

    /**
     * Returns a normal ArrayIterator that will loop over all keys set.
     *
     * @return ArrayIterator
     */
    public function getIterator() {

    }

}
