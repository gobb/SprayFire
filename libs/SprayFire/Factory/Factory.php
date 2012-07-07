<?php

/**
 * An interface to allow a common API for all factory objects.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Factory;

interface Factory {

    /**
     * @param string $objectName
     * @param array $options
     * @return object
     */
    public function makeObject($objectName, array $options = array());

    /**
     *@return string
     */
    public function getObjectType();

}