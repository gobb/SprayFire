<?php

/**
 * @file
 * @brief A file holding an interface to hold a numerically index collection of objects.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Core\Structure;

/**
 * @brief Represents the interface for a data structure that holds a numeric-indexed
 * collection of objects.
 */
interface ObjectCollection extends \Countable, \Traversable {

    /**
     * @param $Object SprayFire.Core.Object to add to the collection
     * @throws InvalidArgumentException if the object type for the collection should be restricted
     *         and the object type passed does not match the restricted type
     */
    public function addObject(\SprayFire\Core\Object $Object);

    /**
     * @param $Object SprayFire.Core.Object object to test if the collection contains
     * @return true if the object is contained, false if it is not
     */
    public function containsObject(\SprayFire\Core\Object $Object);

    /**
     * @param $index An unsigned integer representing the object index to remove
     * @return true if the object is removed, false it is not
     */
    public function removeIndex($index);

    /**
     * @brief This method should remove **all** copies of the passed $Object from
     * the collection.
     *
     * @param $Object SprayFire.Core.Object object to remove all copies of
     * @return true if all objects equal to \a $Object are removed, false if not
     */
    public function removeObject(\SprayFire\Core\Object $Object);

    /**
     * @param $index An unsigned integer representing the object index to
     *        retrieve
     * @return SprayFire.Core.Object if the $index is found, null if the $index is not stored
     * @throws InvalidArgumentException thrown if the index is a string or negative number
     */
    public function getObject($index);

    /**
     * @param $Object SprayFire.Core.Object
     * @return Numeric index of the object or false if the object does not exist in collection
     */
    public function getIndex(\SprayFire\Core\Object $Object);

    /**
     * @return true if the collection has no members, false if it does
     */
    public function isEmpty();

}