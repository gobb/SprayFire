<?php

/**
 * @file
 * @brief Framework's implementation of an ObjectStorage data structure.
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
 * @brief The framework's primary implementation to store framework objects.
 *
 * @details
 * Allows for the associating of framework objects with a key and the retrieval
 * of that object using that key.  Also allows for the removal of an object
 * associated with a key and iterating over the stored objects.
 *
 * @uses SprayFire.Core.Object
 * @uses SprayFire.Core.GenericMap
 * @uses SprayFire.Core.ObjectTypeValidator
 */
class RestrictedMap extends \SprayFire\Core\Structure\GenericMap {

    protected $Validator;

    public function __construct($namespacedType) {
        try {
            $ReflectedType = new \ReflectionClass($namespacedType);
            $this->Validator = new \SprayFire\Core\ObjectTypeValidator($ReflectedType);
        } catch (\ReflectionException $ReflectExc) {
            throw new \SprayFire\Exception\TypeNotFoundException('The type passed, ' . $namespacedType . ', could not be found or loaded.');
        }
    }

    /**
     * @brief Determines whether or not the passed \a $Object \a is a proepr
     * object for this storage type, adds the \a $Object \a if it does and
     * throws an exception if it does not.
     *
     * @details
     * This method will only throw one exception type but that exception may
     * be thrown for one of two reasons; (1) the \a $key \a is not a valid string
     * or is empty (2) the \a $Object \a does not properly implement the type
     * injected into the constructor
     *
     * @param $key A string or numeric index
     * @param $Object Should implement SprayFire.Core.Object
     * @throws InvalidArgumentException
     * @return SprayFire.Core.Object
     */
    public function setObject($key, \SprayFire\Core\Object $Object) {
        $this->Validator->throwExceptionIfObjectNotParentType($Object);
        parent::setObject($key, $Object);
    }



}