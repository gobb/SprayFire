<?php

/**
 * @file
 * @brief An interface for classes that should have data accessible through overloaded
 * properties.
 */

namespace SprayFire\Structure;

/**
 * @brief An interface that forces the implementation of the magic methods that allow
 * an object to access non-existing or inaccessible properties.
 *
 * @details
 * Please note, implementing this interface does not necessarily mean that the object
 * is mutable or immutable, simply that the implementing object must do something
 * when these methods are called.
 *
 * Optional methods should throw a SprayFire.Exceptions.UnsupportedOperationException.
 *
 * @see http://php.net/manual/en/language.oop5.overloading.php
 */
interface Overloadable {

    /**
     * @param $key string
     * @return mixed
     */
    public function __get($key);

    /**
     * @param $key string
     * @param $value mixed
     * @return mixed
     * @throws SprayFire.Exceptions.UnsupportedOperationException
     */
    public function __set($key, $value);

    /**
     * @param $key string
     * @return boolean
     */
    public function __isset($key);

    /**
     * @param $key string
     * @return boolean
     * @throws SprayFire.Exceptions.UnsupportedOperationException
     */
    public function __unset($key);

}