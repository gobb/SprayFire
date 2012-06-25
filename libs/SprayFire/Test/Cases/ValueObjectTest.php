<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of ValueObjectTest
 */

namespace SprayFire\Test\Cases;

class ValueObjectTest extends \PHPUnit_Framework_TestCase {

    public function testBasicValueObjectWithCorrectDataAndType() {
        $data = array();
        $data['name'] = 'Charles Sprayberry';
        $data['age'] = 28;
        $data['isAlive'] = true;
        $data['gender'] = 'Male';
        $data['weight'] = 125.0;

        $Charles = new \SprayFire\Test\Helpers\TestValueObject($data);
        $this->assertSame('Charles Sprayberry', $Charles->name);
        $this->assertSame(28, $Charles->age);
        $this->assertSame(true, $Charles->isAlive);
        $this->assertSame('Male', $Charles->gender);
        $this->assertSame(125.0, $Charles->weight);
    }

    public function testBasicValueObjectSettingInaccessibleProperty() {
        $data = array();
        $data['notAccessible'] = 'something else';

        $Data = new \SprayFire\Test\Helpers\TestValueObject($data);
        $expected = \md5('SprayFire');
        $actual = $this->getNotAccessiblePropertyValue($Data);
        $this->assertSame($expected, $actual);
    }

    public function testBasicValueObjectCastingDataTypes() {
        $data = array();
        $data['name'] = new \SprayFire\Test\Cases\Name('Charles Sprayberry');
        $data['age'] = '28';
        $data['isAlive'] = '1';
        $data['gender'] = 'Male';
        $data['weight'] = '125.0';

        $Charles = new \SprayFire\Test\Helpers\TestValueObject($data);
        $this->assertSame('Charles Sprayberry', $Charles->name);
        $this->assertSame(28, $Charles->age);
        $this->assertSame(true, $Charles->isAlive);
        $this->assertSame('Male', $Charles->gender);
        $this->assertSame(125.0, $Charles->weight);
    }

    public function testBasicValueObjectGettingNotAccessibleProperty() {
        $Data = new \SprayFire\Test\Helpers\TestValueObject(array());
        $this->assertSame(null, $Data->notAccessible);
    }

    public function testBasicValueObjectTestingIfPropertyIsSet() {
        $Data = new \SprayFire\Test\Helpers\TestValueObject(array());
        $this->asserttrue(isset($Data->name));
    }

    protected function getNotAccessiblePropertyValue(\SprayFire\Test\Helpers\TestValueObject $Object) {
        $Reflection = new \ReflectionObject($Object);
        $Property = $Reflection->getProperty('notAccessible');
        $Property->setAccessible(true);
        return $Property->getValue($Object);
    }

}


class Name {

    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function __toString() {
        return $this->name;
    }
}