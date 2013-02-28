<?php

/**
 * Abstract implementation of \SprayFire\Object designed to provide basic functionality
 * across a variety of objects, both in the framework and your applications.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\StdLib;

/**
 * It is recommended that classes needing to implement \SprayFire\Object extend
 * this class.
 *
 * This class will never implement any functionality not specified in the SprayFire.Object
 * interface.  It is explicitly designed in such a way that it should be reasonable
 * for all objects to trace their inheritance back through this object if interfaces
 * they implement require \SprayFire\Object.
 *
 * @package SprayFire
 * @subpackage StdLib
 *
 * @codeCoverageIgnore
 */
abstract class CoreObject implements \SprayFire\Object {

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
     * Default implementation, compares the \SprayFire\CoreObject::hashCode()
     * return value to the passed $CompareObject.
     *
     * If your objects need to implement a Comparator be sure to override this
     * implementation.
     *
     * @param \SprayFire\Object $CompareObject
     * @return boolean
     */
    public function equals(\SprayFire\Object $CompareObject) {
        return $this->hashCode() === $CompareObject->hashCode();
    }

    /**
     * Returns the fully namespaced class name.
     *
     * We are using get_class() over the __CLASS__ magic constant as the constant
     * will return the name of the class this method is implemented in and not
     * the name of the class we are invoking the object on.
     *
     * @return string
     */
    public function __toString() {
        return \get_class($this);
    }

}
