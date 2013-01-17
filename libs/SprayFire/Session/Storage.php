<?php

/**
 * Interface that provides an abstraction and additional functionality for the
 * $_SESSION superglobal.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Session;

use \SprayFire\Object as SFObject,
    \ArrayAccess as ArrayAccess,
    \Countable as Countable,
    \Serializable as Serializable,
    \Traversable as Traversable;

/**
 * @package SprayFire
 * @subpackage Session
 */
interface Storage extends SFObject, ArrayAccess, Countable, Serializable, Traversable {

    /**
     * Should ensure that the $_SESSION superglobal is properly reset back to an
     * array for proper serialization.
     */
    public function __destruct();

    /**
     * Clear all data out of the session storage.
     *
     * @return void
     */
    public function clear();

    /**
     * Clear data associated to a specific $key from the session storage.
     *
     * @param string $key
     * @return void
     */
    public function clearKey($key);

    /**
     * Determine whether or not the session storage can be written to.
     *
     * @return boolean
     */
    public function isImmutable();

    /**
     * Will make the session storage immutable, causing an exception to be thrown
     * if the session is written to after this method is invoked.
     *
     * @return void
     */
    public function makeImmutable();

}
