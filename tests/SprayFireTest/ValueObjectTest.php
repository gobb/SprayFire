<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of ValueObjectTest
 */

namespace SprayFireTest;

use \SprayFireTest\Helpers as FireTestHelpers,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

class ValueObjectTest extends PHPUnitTestCase {

    public function testBasicValueObjectWithCorrectDataAndType() {
        $data = array();
        $data['name'] = 'Charles Sprayberry';
        $data['age'] = 28;
        $data['isAlive'] = true;
        $data['gender'] = 'Male';
        $data['weight'] = 125.0;

        $Charles = new FireTestHelpers\TestValueObject($data);
        $this->assertSame('Charles Sprayberry', $Charles->name);
        $this->assertSame(28, $Charles->age);
        $this->assertSame(true, $Charles->isAlive);
        $this->assertSame('Male', $Charles->gender);
        $this->assertSame(125.0, $Charles->weight);
    }

    public function testBasicValueObjectSettingInaccessibleProperty() {
        $data = array();
        $data['notAccessible'] = 'something else';

        $Data = new FireTestHelpers\TestValueObject($data);
        $expected = \md5('SprayFire');
        $actual = $this->getNotAccessiblePropertyValue($Data);
        $this->assertSame($expected, $actual);
    }

    public function testBasicValueObjectCastingDataTypes() {
        $data = array();
        $data['name'] = new Name('Charles Sprayberry');
        $data['age'] = '28';
        $data['isAlive'] = '1';
        $data['gender'] = 'Male';
        $data['weight'] = '125.0';

        $Charles = new FireTestHelpers\TestValueObject($data);
        $this->assertSame('Charles Sprayberry', $Charles->name);
        $this->assertSame(28, $Charles->age);
        $this->assertSame(true, $Charles->isAlive);
        $this->assertSame('Male', $Charles->gender);
        $this->assertSame(125.0, $Charles->weight);
    }

    public function testBasicValueObjectGettingNotAccessibleProperty() {
        $Data = new FireTestHelpers\TestValueObject(array());
        $this->assertSame(null, $Data->notAccessible);
    }

    public function testBasicValueObjectTestingIfPropertyIsSet() {
        $Data = new FireTestHelpers\TestValueObject(array());
        $this->assertTrue(isset($Data->name));
    }

    public function testBasicValueObjectTestingIfInaccessiblePropertyIsSet() {
        $Data = new FireTestHelpers\TestValueObject(array());
        $this->assertFalse(isset($Data->notAccessible));
    }

    public function testBasicValueObjectSettingProperty() {
        $Data = new FireTestHelpers\TestValueObject(array());
        $this->setExpectedException('\\SprayFire\\Exception\\UnsupportedOperationException');
        $Data->name = 'Charles';
    }

    public function testBasicValueObjectDestroyingProperty() {
        $Data = new FireTestHelpers\TestValueObject(array());
        $this->setExpectedException('\\SprayFire\\Exception\\UnsupportedOperationException');
        unset($Data->name);
    }

    public function testValueObjectsBeingEqual() {
        $data = array();
        $data['name'] = 'Charles Sprayberry';
        $data['age'] = 28;
        $data['isAlive'] = true;
        $data['gender'] = 'Male';
        $data['weight'] = 125.0;

        $CharlesOne = new FireTestHelpers\TestValueObject($data);
        $CharlesTwo = new FireTestHelpers\TestValueObject($data);

        $this->assertTrue($CharlesOne->equals($CharlesTwo));
    }

    public function testValueObjectsNotBeingEqual() {
        $dataOne = array();
        $dataOne['name'] = 'Charles Sprayberry';
        $dataOne['age'] = 28;
        $dataOne['isAlive'] = true;
        $dataOne['gender'] = 'Male';
        $dataOne['weight'] = 125.0;

        $dataTwo = array();
        $dataTwo = array();
        $dataTwo['name'] = 'Charles Sprayberry';
        $dataTwo['age'] = 4;
        $dataTwo['isAlive'] = true;
        $dataTwo['gender'] = 'Male';
        $dataTwo['weight'] = 25.0;

        $CharlesOne = new FireTestHelpers\TestValueObject($dataOne);
        $CharlesTwo = new FireTestHelpers\TestValueObject($dataTwo);

        $this->assertFalse($CharlesOne->equals($CharlesTwo));
    }

    protected function getNotAccessiblePropertyValue(FireTestHelpers\TestValueObject $Object) {
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
