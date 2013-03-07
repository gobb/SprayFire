<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Helpers;

/**
 * @brief
 */
class TestValueObject extends \SprayFire\StdLib\ValueObject {

    protected $name = 'John Doe';

    protected $age = 0;

    protected $gender = null;

    protected $isAlive = true;

    protected $weight = 0.0;

    protected $notAccessible;

    public function __construct(array $data) {
        parent::__construct($data);
        $this->notAccessible = \md5('SprayFire');
    }

    public function toArray() {
        
    }

    protected function getAccessibleProperties() {
        $properties = array();
        $properties['name'] = 'string';
        $properties['age'] = 'int';
        $properties['gender'] = 'string';
        $properties['isAlive'] = 'boolean';
        $properties['weight'] = 'float';
        return $properties;
    }
}
