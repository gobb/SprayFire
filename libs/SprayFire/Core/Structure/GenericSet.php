<?php

/**
 * @file
 * @brief Holds a class designed to store a unique set of generic SprayFire.Core.Object
 * objects.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Core\Structure;

/**
 * @brief Ensures that the collection of objects do not have duplicate values.
 */
class GenericSet extends \SprayFire\Core\Structure\GenericCollection {

    /**
     * @param $Object \SprayFire\Core\Object
     * @return The index for \a $Object or false if it exists in the Set
     */
    public function addObject(\SprayFire\Core\Object $Object) {
        if ($this->containsObject($Object)) {
            return false;
        }
        return parent::addObject($Object);
    }

}