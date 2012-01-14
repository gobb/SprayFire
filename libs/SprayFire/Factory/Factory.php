<?php

/**
 * @file
 * @brief Holds an interface to allow a common API for all factory objects.
 */

namespace SprayFire\Factory;

interface Factory {

    /**
     * @param $objectName namespaced class to create from the factory
     * @return An object representing the one requested or a NullObject of the given type
     */
    public function makeObject($objectName, array $options = array());

    /**
     *@return The complete, namespaced class that this factory will produce.
     */
    public function getObjectName();

}