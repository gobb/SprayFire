<?php

/**
 * Interface to allow the dynamic creation of objects and to retrieve information
 * about the type of objects created by the implementation.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Factory;

use \SprayFire\Object;

/**
 * @package SprayFire
 * @subpackage Factory.Interface
 */
interface Factory extends Object {

    /**
     * If the appropriate object could not be successfully created we should return
     * a Null Object implementation or throw an exception, at no point should a
     * value be returned from this method that cannot be invoked as if it were
     * an object.
     *
     * @param string $objectName
     * @param array $options
     * @return object
     */
    public function makeObject($objectName, array $options = []);

    /**
     * Should return the Java or PHP style name for the type of object the factory
     * creates.
     *
     *@return string
     */
    public function getObjectType();

    /**
     * Should return the specific type for the object returned if an error was
     * encountered creating the requested object.
     *
     * @return string
     */
    public function getNullObjectType();

}
