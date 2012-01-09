<?php

/**
 * @file
 * @brief A file holding a class that holds a set of objects; restricted to a specific
 * type.
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
 * @brief This class will hold a collection of unique objects and ensure that those
 * objects are of a specific interface or class.
 *
 * @uses ReflectionClass
 * @uses InvalidArgumentException
 * @uses SprayFire.Core.Structure.GenericSet
 * @uses SprayFire.Core.ObjectTypeValidator
 * @uses SprayFire.Exception.TypeNotFoundException
 */
class RestrictedSet extends \SprayFire\Core\Structure\GenericSet {

    /**
     * @brief Used to validate that an object added to the set adheres to a specific
     * type.
     *
     * @propery SprayFire.Core.ObjectTypeValidator
     */
    protected $TypeValidator;

    /**
     * @param $parentType A string representing the interface or class that this
     *        set should be restricted to
     * @param $initialSize The number of buckets the set should be initialized to
     * @throws SprayFire.Exception.TypeNotFoundException
     */
    public function __construct($parentType, $initialSize = 32) {
        parent::__construct($initialSize);
        try {
            $ReflectedType = new \ReflectionClass($parentType);
            $this->TypeValidator = new \SprayFire\Core\Util\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The parent type for this set, ' . $parentType . ', could not be loaded.');
        }

    }

    /**
     * @param $Object SprayFire.Core.Object
     * @return The numeric index or false on failure
     * @throws InvalidArgumentException
     */
    public function addObject(\SprayFire\Core\Object $Object) {
        $this->TypeValidator->throwExceptionIfObjectNotParentType($Object);
        return parent::addObject($Object);
    }

}