<?php

/**
 * Abstract implementation of SprayFire.Object.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 * @version 0.1
 * @since 0.1
 */

namespace SprayFire;

use \SprayFire\Object as Object;

/**
 * It is recommended that classes needing to implement SprayFire.Object extend
 * this class.
 *
 * This class will never implement any functionality not specified in the SprayFire.Object
 * interface.  It is explicitly designed in such a way that it should be reasonable
 * for all objects to trace their inheritance back through this object if interfaces
 * they implement require SprayFire.Object.
 */
abstract class CoreObject implements Object {

    /**
     * Returns a unique identifier for the object that will be the same for objects
     * referencing the same spot in memory.
     *
     * @return string
     * @see http://us3.php.net/manual/en/function.spl-object-hash.php
     */
    public final function hashCode() {
        return \spl_object_hash($this);
    }

    /**
     * Default implementation, compares the SprayFire.Core.CoreObject::hashCode()
     * return value to the passed $CompareObject.
     *
     * If your objects need to implement a Comparator be sure to override this
     * implementation.
     *
     * @param SprayFire.Object $CompareObject Object to compare to for equality
     * @return boolean
     */
    public function equals(Object $CompareObject) {
        return $this->hashCode() === $CompareObject->hashCode();
    }

    /**
     * Returns the fully namespaced class name.
     *
     * @return string
     */
    public function __toString() {
        return \get_class($this);
    }

}