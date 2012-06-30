<?php

/**
 * An implementation of the base SprayFire.Object interface
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire;

use \SprayFire\Object as Object;

abstract class CoreObject implements Object {

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
    public function equals(Object $CompareObject) {
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