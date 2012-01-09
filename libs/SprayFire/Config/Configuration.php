<?php

/**
 * @file
 * @brief Holds an interface that allows for the retrieval of data from an object
 * through array or object notation.
 */

namespace SprayFire\Config;

/**
 * @brief An interface that requires implementing objects to have data accessible
 * through array access and object notation.
 *
 * @details
 * Implementations of this interface should be immutable.  Only data passed in
 * a constructor depedency should be worked with, no new data to be set or
 * existing data to be changed or removed.
 *
 * @uses ArrayAccess
 * @uses SprayFire.Structure.Overloadable
 */
interface Configuration extends \ArrayAccess, \SprayFire\Structure\Overloadable {

}