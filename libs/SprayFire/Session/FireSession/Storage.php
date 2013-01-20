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
     * Key/value pairs stored.
     *
     * @property array
     */
    protected $data = array();

    /**
     * Flag to determine if the session storage is allowed to be written to.
     *
     * True will cause the session storage not to be able to be written to.
     *
     * @property boolean
     */
    protected $isImmutable = false;

    /**
     * Determine whether a key identified by $offset exists in the storage or not.
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->data[(string) $offset]);
    }

    /**
     * Will return the value stored against $offset or null if no value exists.
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        if ($this->offsetExists($offset)) {
            return $this->data[(string) $offset];
        }
        return null;
    }

    /**
     * Will set a $value against
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value) {
        $this->data[(string) $offset] = $value;
    }

    /**
     * Will remove a value stored against $offset causing it to return null if
     * retrieved without resetting.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset) {
        $offset = (string) $offset;
        $this->data[$offset] = null;
        unset($this->data[$offset]);
    }

    /**
     * Returns the number of keys stored for this session.
     *
     * @return integer
     */
    public function count() {
        return \count($this->data);
    }

    /**
     * Clear all data out of the session storage.
     *
     * @return void
     */
    public function clear() {
        $this->data = array();
    }

    /**
     * Clear data associated to a specific $key from the session storage.
     *
     * @param string $key
     * @return void
     */
    public function clearKey($key) {
        $this->offsetUnset($key);
    }

    /**
     * Determine whether or not the session storage can be written to.
     *
     * @return boolean
     */
    public function isImmutable() {
        return $this->isImmutable;
    }

    /**
     * Will make the session storage immutable, causing an exception to be thrown
     * if the session is written to after this method is invoked.
     *
     * @return void
     */
    public function makeImmutable() {
        $this->isImmutable = true;
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
