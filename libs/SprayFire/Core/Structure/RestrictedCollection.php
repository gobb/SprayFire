<?php

/**
 * @file
 * @brief An extension of SprayFire.Core.Structure.GenericCollection that allows
 * only a restricted type of object to be stored.
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
 * @brief A collection that allows any number of a restricted type of object to be
 * stored.
 *
 * @uses ReflectionClass
 * @uses SprayFire.Core.Object
 * @uses SprayFire.Core.Structure.GenericCollection
 * @uses SprayFire.Core.ObjectTypeValidator
 */
class RestrictedCollection extends \SprayFire\Core\Structure\GenericCollection {

    /**
     * @brief A SprayFire.Core.ObjectTypeValidator used to ensure that the passed
     * objects implement the correct interface or extends the correct class.
     *
     * @property $TypeValidator
     */
    protected $TypeValidator;

    /**
     * @param $parentType A string representing the interface or class all objects should be
     * @param $initialSize Number of buckets to initially create
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    public function __construct($parentType, $initialSize = 32) {
        parent::__construct($initialSize);
        try {
            $ReflectedType = new \ReflectionClass($parentType);
            $this->TypeValidator = new \SprayFire\Core\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The passed type, ' . $parentType . ', could not be loaded.');
        }
    }

    /**
     * @param $Object \SprayFire\Core\Object
     * @return numeric index of \a $Object or false if error occurred
     * @throws InvalidArgumentException if the \a $Object is not the correct type
     *         for this collection
     */
    public function addObject(\SprayFire\Core\Object $Object) {
        $this->TypeValidator->throwExceptionIfObjectNotParentType($Object);
        return parent::addObject($Object);
    }

}