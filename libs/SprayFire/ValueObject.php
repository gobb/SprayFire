<?php

/**
 * A base abstract class implementing basic functionality for a Value Object
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire;

use \SprayFire\Object as Object,
    \SprayFire\CoreObject as CoreObject;

abstract class ValueObject extends CoreObject {

    /**
     * An associative array holding the property as the key and the data type for
     * that property as the value.
     *
     * @property array
     */
    private $accessibleProperties;

    /**
     * Accepts an associative array with the property as the key and the value for
     * that property as the value.
     *
     * @param array $data
     */
    public function __construct(array $data) {
        $this->accessibleProperties = $this->getAccessibleProperties();
        foreach ($this->accessibleProperties as $property => $type) {
            if (\array_key_exists($property, $data)) {
                $this->$property = $data[$property];
                \settype($this->$property, $type);
            }
        }
    }

    /**
     * If the requested $property is accessible the value for that property will
     * be returned, otherwise null will be returned.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        if (\array_key_exists($property, $this->accessibleProperties)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * @param string $property
     * @return boolean
     */
    public function __isset($property) {
        if (\array_key_exists($property, $this->accessibleProperties)) {
            return isset($this->$property);
        }
        return false;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    public final function __set($property, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not change the value of this object, it is immutable.');
    }

    /**
     * @param string $property
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    public final function __unset($property) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not change the value of this object, it is immutable.');
    }

    /**
     * Overridden to ensure that ValueObject equality is based on the values stored
     * by each object.
     *
     * @param SprayFire.Object $Object
     * @return boolean
     */
    public function equals(Object $Object) {
        foreach ($this->accessibleProperties as $property => $type) {
            if ($this->$property !== $Object->$property) {
                return false;
            }
        }
        return true;
    }

    /**
     * Return an array representation of the Value Object
     *
     * @return array
     */
    public abstract function toArray();

    /**
     * Return an associative array with the key matching to an explicitly declared
     * class property and the value of that key being the data type that the value
     * should be.
     *
     * @return array
     */
    protected abstract function getAccessibleProperties();

}