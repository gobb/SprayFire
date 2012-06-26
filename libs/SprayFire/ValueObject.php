<?php

/**
 * A base abstract class implementing basic functionality for a Value Object
 *
 * @author Charles Sprayberry
 */

namespace SprayFire;

abstract class ValueObject extends \SprayFire\CoreObject {

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
     * @param $data array
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
     * @param $property string
     * @return mixed
     */
    public function __get($property) {
        if (\array_key_exists($property, $this->accessibleProperties)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * @param $property string
     * @return boolean
     */
    public function __isset($property) {
        if (\array_key_exists($property, $this->accessibleProperties)) {
            return isset($this->$property);
        }
        return false;
    }

    /**
     * @param $property string
     * @param $value mixed
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    public final function __set($property, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not change the value of this object, it is immutable.');
    }

    /**
     * @param $property string
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    public final function __unset($property) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not change the value of this object, it is immutable.');
    }

    /**
     * Overridden to ensure that ValueObject equality is based on the values stored
     * by each object.
     *
     * @param $Object SprayFire.Object
     * @return boolean
     */
    public function equals(\SprayFire\Object $Object) {
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