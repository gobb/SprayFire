<?php

/**
 * @file
 * @brief A simple key/value storage object that extends SprayFire.Core.Structure.DataStorage
 * and does not allow the data associated to be changed after the object has been
 * constructed.
 */

namespace SprayFire\Structure\Storage;

/**
 * @brief An object that allows for data to be stored and to be assured that
 * the data is not mutable.
 *
 * @details
 * This object is immutable by the fact that after the object is constructed
 * attempting to __set the object or offsetSet the object's properties will
 * results in a SprayFire.Exceptions.UnsupportedOperationException will
 * be thrown.  If a class extends this please ensure that it is a truly immutable
 * object and does not have any "backdoors".
 *
 * @uses SprayFire.Structure.Storage.DataStorage
 */
class ImmutableStorage extends \SprayFire\Structure\Storage\DataStorage {

    /**
     * @brief Accepts an array of data to store and gives the calling code the option to
     * convert all inner arrays into ImmutableStorage objects.
     *
     * @param $data array
     * @param $convertDeep boolean
     */
    public function __construct(array $data, $convertDeep = true) {
        if ((boolean) $convertDeep) {
            $data = $this->convertDataDeep($data);
        }
        if (!\is_array($data)) {
            throw new \UnexpectedValueException('The data returned from convertDataDeep must be an array.');
        }
        parent::__construct($data);
    }

    /**
     * @param $key string
     * @param $value mixed
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    protected function set($key, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('Attempting to set the value of an immutable object.');
    }

    /**
     * @param $key string
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    protected function removeKey($key) {
        throw new \SprayFire\Exception\UnsupportedOperationException('Attempting to remove the value of an immutable object.');
    }

    /**
     * @brief Converts all arrays in \a $data \a to ImmutableStorage objects,
     * allowing for the chaining of properties in the created object.
     *
     * @details
     * Note that if you extend ImmutableStorage and override this method an array
     * value MUST be returned or a SprayFire.Exceptions.UnexpectedValueException
     * will be thrown by the class constructor.  If self::__construct() is overridden
     * as well and the data from convertDataDeep is not an array you will receive a
     * type hint compile error when parent::__construct() is called.
     *
     * @param $data array
     * @return array
     */
    protected function convertDataDeep(array $data) {
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $data[$key] = $this->convertArrayToImmutableObject($value);
            }
        }
        return $data;
    }

    /**
     * @brief Will convert the passed array, and all arrays within that array,
     * to a SprayFire.Datastructs.ImmutableStorage object.
     *
     * @param $data array
     * @return SprayFire.Core.Structures.ImmutableStorage
     */
    private function convertArrayToImmutableObject(array $data) {
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $data[$key] = $this->convertArrayToImmutableObject($value);
            }
        }
        return new ImmutableStorage($data);
    }

}