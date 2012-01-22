<?php

/**
 * @file
 * @brief Framework object to allow working with PHP superglobals, or any array,
 * as an object; changes to the array change the object and changes to the object
 * change the array.
 */

namespace SprayFire\Request;

/**
 * @brief Will allow for an array to be passed, the values of that array to be
 * treated as an object, and for changes to the array or the object to make
 * changes to the other.
 *
 * @details
 * Designed to provide a base for objects needing to work with PHP superglobals
 * and perform methods or validation on that data, as an example.
 *
 * It is important to realize that due to the implementation of this object and
 * that of SprayFire.Core.Strcuture.MutableStorage it is not possible to convert
 * arrays in data passed into MutableStorage objects.  You will have to implement
 * this yourself if you would like this functionality.  Ultimately, I would not do
 * this however, as the array can ultimately be changed outside of this class.
 *
 * @uses SprayFire.Core.Structure.MutableStorage
 */
abstract class HttpData extends \SprayFire\Structure\MutableStorage {

    /**
     * @param $data An array passed by reference
     */
    public function __construct(array &$data) {
        $this->data =& $data;
    }

}