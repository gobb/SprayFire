<?php

/**
 * @file
 * @brief Provides a generic means to store data through object (->) or array ([])
 * notation.
 */

namespace SprayFire\Structure\Storage;

/**
 * @brief Stores data in a property and allows access to that data array notation
 * ONLY.
 *
 * @details
 * This class provides a generic means to retrieve data based on a unique
 * \a $key and determine if that \a $key has a non-null element associated
 * with it.  Extending classes should provide the exact implementation details
 * for how that \a $key and \a $value should be added to the appropriate property.
 *
 * You should really be using this data structure as a type of array with finer
 * grained setting and getting rules.  Perhaps an implementation only allows certain
 * values to be set or no values to be set at all.  If you have methods to manipulate
 * the data structure, e.g., add(), as compared to manipulating it through array
 * notation you should really evaluate whether or not this data structure is the
 * best suited for your needs.
 *
 * @uses ArrayIterator
 * @uses Countable
 * @uses IteratorAggregate
 * @uses SprayFire.Core.Util.CoreObject
 */
abstract class DataStorage extends \SprayFire\Core\Util\CoreObject implements \ArrayAccess, \Countable, \IteratorAggregate {

    /**
     * An array holding the data being stored.
     *
     * @property $data
     */
    protected $data = array();

    /**
     * @brief Should accept an array that has the information to store, or
     * an empty array if an empty structure is to be created.
     *
     * @details
     * If an associative indexed array is passed it is recommended that you
     * only set string properties and do not use the structure as an array
     * with numerically indexed keys.  However, the opposite applies if the
     * structure is to hold numerically indexed keys work with it as an array
     * and only store numerically indexed keys in it.
     *
     * @param $data array
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param $key string
     * @return mixed
     */
    public function offsetGet($key) {
        if ($this->keyInData($key)) {
            return $this->data[$key];
        }
        return NULL;
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function offsetExists($key) {
        if ($this->keyInData($key)) {
            return isset($this->data[$key]);
        }
        return false;
    }

    /**
     * @brief Required by the Countable interface, will return the number of data elements
     * stored in $data; please note that this is not a recursive count.
     *
     * @return int
     */
    public function count() {
        return \count($this->data);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    /**
     * @brief An internally used method to determine if a given \a $key exists
     * in the stored data.
     *
     * @details
     * If your class needs to override this method please ensure a true boolean
     * type is returned.
     *
     * @param $key string
     * @return boolean
     */
    protected function keyInData($key) {
        return \array_key_exists($key, $this->data);
    }

    /**
     * @param $key string
     * @param $value mixed
     * @return mixed
     */
    public function __set($key, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not access data in this way, please use array access notation.');
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function __unset($key) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not access data in this way, please use array access notation.');
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function __isset($key) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not access data in this way, please use array access notation.');
    }

    /**
     * @param $key string
     * @return mixed
     */
    public function __get($key) {
        throw new \SprayFire\Exception\UnsupportedOperationException('You may not access data in this way, please use array access notation.');
    }

}