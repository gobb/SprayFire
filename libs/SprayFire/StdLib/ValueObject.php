<?php

/**
 * A base abstract class implementing functionality for a Value Object
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\StdLib;

use \SprayFire\Exception as SFException;

/**
 * @package SprayFire
 */
abstract class ValueObject extends CoreObject {

    /**
     * An associative array holding the property as the key and the data type for
     * that property as the value.
     *
     * @property array
     */
    private $accessibleProperties;

    private $errorMessage = 'You may not change property values of %s, it is immutable.';

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
     * @throws \SprayFire\Exception\UnsupportedOperationException
     */
    public final function __set($property, $value) {
        $message = \sprintf($this->errorMessage, \get_class($this));
        throw new SFException\UnsupportedOperationException($message);
    }

    /**
     * @param string $property
     * @throws \SprayFire\Exception\UnsupportedOperationException
     */
    public final function __unset($property) {
        $message = \sprintf($this->errorMessage, \get_class($this));
        throw new SFException\UnsupportedOperationException($message);
    }

    /**
     * Overridden to ensure that ValueObject equality is based on the values stored
     * by each object.
     *
     * @param \SprayFire\Object $Object
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
