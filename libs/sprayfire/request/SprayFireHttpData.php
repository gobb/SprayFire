<?php

/**
 * @file
 * @brief The framework's implementation of the libs.sprayfire.request.HttpData
 * interface.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */
namespace libs\sprayfire\request;
use \ArrayIterator as ArrayIterator;
use \IteratorAggregate as IteratorAggregate;
use libs\sprayfire\datastructs\MutableStorage as MutableStorage;
use libs\sprayfire\request\HttpData as HttpData;

    /**
     * @brief Will allow for an array to be passed, the values of that array to be
     * treated as an object, and for changes to the array or the object to make
     * changes to the other.
     */
    class SprayFireHttpData extends MutableStorage implements IteratorAggregate, HttpData {

        /**
         * @param $data An array passed by reference
         */
        public function __construct(array &$data) {
            $this->data =& $data;
        }

        /**
         * @return \ArrayIterator
         */
        public function getIterator() {
            return new ArrayIterator($this->data);
        }

    }

    // End SprayFireHttpData

// End libs.sprayfire