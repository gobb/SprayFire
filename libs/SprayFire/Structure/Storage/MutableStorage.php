<?php

/**
 * @file
 * @brief A class that extends SprayFire.Core.Structure.DataStorage and allows
 * for any data to be manipulated, removed or added to the structure.
 */

namespace SprayFire\Structure\Storage;

/**
 * @brief A simple data storage object that holds key/value pairs and allows additional
 * keys to be added and existing keys to be manipulated or removed.
 */
class MutableStorage extends \SprayFire\Structure\Storage\DataStorage {

    /**
     * @param $key string
     * @param $value mixed
     * @return mixed
     */
    protected function offsetSet($key, $value) {
        if (\is_null($key)) {
            $key = \count($this->data);
        }
        return $this->data[$key] = $value;
    }

    /**
     * @param $key string
     */
    protected function offsetUnset($key) {
        if ($this->keyInData($key)) {
            unset($this->data[$key]);
        }
    }

}