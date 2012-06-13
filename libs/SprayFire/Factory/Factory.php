<?php

/**
 * An interface to allow a common API for all factory objects.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Factory;

interface Factory {

    /**
     * @param $objectName string Namespaced class to create
     * @return Object Should be of the type for this Factory
     */
    public function makeObject($objectName, array $options = array());

    /**
     *@return string The complete, namespaced class that this factory will produce.
     */
    public function getObjectType();

}