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
    \Traversable as Traversable;

/**
 *
 *
 * @package SprayFire
 * @subpackage Session
 */
interface Storage extends SFObject, ArrayAccess, Countable, Traversable {

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
     *
     *
     * @param string $key
     * @return void
     */
    public function clearKey($key);

    public function isImmutable();

    public function makeImmutable();

}
