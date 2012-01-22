<?php

/**
 * @file
 * @brief The framework's implementation of the libs.sprayfire.core.Object interface.
 */

namespace SprayFire\Core\Util;

/**
 * @brief SprayFire's implementation of the libs.sprayfire.core.Object interface,
 * allowing easy access to some of the functionality required by this interface
 * simply by extending CoreObject.
 *
 * @details
 * Virtually all of the framework objects used by your application will extend
 * this class.  So, if you use the framework as its intended to be used this
 * functionality should be provided to you "out-of-the-box".  When you start
 * creating your own objects be sure they somehow trace back to this object
 * or an implementation of libs.sprayfire.core.Object or unexpected consequences
 * may occur.
 */
abstract class CoreObject implements \SprayFire\Object {

    /**
     * @return A unique identifying string based on the internal memory pointer
     * @see http://us3.php.net/manual/en/function.spl-object-hash.php
     */
    public final function hashCode() {
        $hashCode = \spl_object_hash($this);
        return $hashCode;
    }

    /**
     * @brief Default implementation, compares the SprayFire.Core.CoreObject::hashCode()
     * return value to the passed \a $CompareObject.
     *
     * @details
     * If your objects need to implement a Comparator be sure to override the
     * implementation of this class.
     *
     * @param $CompareObject A SprayFire.Core.Object to compare to this one for equality
     * @return True if the calling object and \a $CompareObject are equal, false if not
     */
    public function equals(\SprayFire\Object $CompareObject) {
        $thisHash = $this->hashCode();
        $compareHash = $CompareObject->hashCode();
        return $thisHash === $compareHash;
    }

    /**
     * @return A string representation of the calling object
     */
    public function __toString() {
        return \get_class($this);
    }

}