<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.3.
 *
 * SprayFire makes use of namespaces, a custom-built ORM layer, a completely
 * object oriented approach and minimal invasiveness so you can make the framework
 * do what YOU want to do.  Some things we take seriously over here at SprayFire
 * includes clean, readable source, completely unit tested implementations and
 * not polluting the global scope.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Core\Structure;

/**
 *
 */
class RestrictedMapTest extends \PHPUnit_Framework_TestCase {

    public function testBasicObjectStorage() {
        $ParentType = '\\SprayFire\\Test\\Helpers\\TestObject';
        $Storage = new \SprayFire\Structure\Map\RestrictedMap($ParentType);

        $expectedInitiationSize = 0;
        $initiationSize = \count($Storage);
        $this->assertSame($expectedInitiationSize, $initiationSize);
        $this->assertTrue($Storage->isEmpty());
        $this->assertFalse($Storage->containsKey('object-one'));

        $FirstAdd = new \SprayFire\Test\Helpers\TestObject();
        $Storage->setObject('object-one', $FirstAdd);

        $expectedSizeAfterFirstAdd = 1;
        $sizeAfterFirstAdd = \count($Storage);
        $this->assertSame($expectedSizeAfterFirstAdd, $sizeAfterFirstAdd);
        $this->assertFalse($Storage->isEmpty());
        $this->assertTrue($Storage->containsKey('object-one'));

        $expectedFirstAddIndex = 'object-one';
        $firstAddIndex = $Storage->getKey($FirstAdd);
        $this->assertSame($expectedFirstAddIndex, $firstAddIndex);

        $SecondAdd = new \SprayFire\Test\Helpers\TestObject();

        $this->assertFalse($Storage->containsObject($SecondAdd));

        $Storage->setObject('object-two', $SecondAdd);

        $expectedSizeAfterSecondAdd = 2;
        $sizeAfterSecondAdd = $Storage->count();
        $this->assertSame($expectedSizeAfterSecondAdd, $sizeAfterSecondAdd);
        $this->assertTrue($Storage->containsObject($SecondAdd));

        $SecondFromGetObject = $Storage->getObject('object-two');
        $this->assertSame($SecondAdd, $SecondFromGetObject);

        $InvalidObject = new \SprayFire\Structure\Storage\ImmutableStorage(array());
        $exceptionThrown = false;
        try {
            $Storage->setObject('invalid-object', $InvalidObject);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
        $this->assertFalse($Storage->containsObject($InvalidObject));
        $this->assertTrue((\count($Storage)) === 2);
    }

    public function testInterfaceObjectStorage() {
        $Type = '\\SprayFire\\Structure\\Overloadable';
        $Storage = new \SprayFire\Structure\Map\RestrictedMap($Type);

        $Storage->setObject('key', new \SprayFire\Structure\Storage\ImmutableStorage(array()));
        $this->assertTrue($Storage->count() === 1);
    }

    public function testLoopingThroughObjectStore() {
        $Type = '\\SprayFire\\Core\\Object';
        $Storage = new \SprayFire\Structure\Map\RestrictedMap($Type);

        $One = new \SprayFire\Test\Helpers\TestObject();
        $Two = new \SprayFire\Test\Helpers\TestObject();
        $Three = new \SprayFire\Test\Helpers\TestObject();
        $Four = new \SprayFire\Test\Helpers\TestObject();
        $Five = new \SprayFire\Test\Helpers\TestObject();

        $Storage->setObject('one', $One);
        $Storage->setObject('two', $Two);
        $Storage->setObject('three', $Three);
        $Storage->setObject('four', $Four);

        $Storage->setObject('two', $Five);

        $this->assertTrue($Storage->containsObject($Five));
        $this->assertFalse($Storage->containsObject($Two));

        $loopRan = false;
        $expectedKeys = array('one', 'three', 'four');
        $expectedObjects = array($One, $Three, $Four);
        $i = 0;
        foreach ($Storage as $key => $value) {
            if (!$loopRan) {
                $loopRan = true;
            }
            if ($key === 'two') {
                $Storage->removeKey('two');
                continue;
            }
            $this->assertSame($expectedKeys[$i], $key, 'The value of key is ' . $key . ' and the expected key is ' . $expectedKeys[$i]);
            $this->assertSame($expectedObjects[$i], $value);
            $i++;
        }

        $this->assertTrue($loopRan);
        $this->assertTrue($Storage->count() === 3);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNullKeyGiven() {
        $key = null;
        $Object = new \SprayFire\Test\Helpers\TestObject;

        $Type = '\\SprayFire\\Test\\Helpers\\TestObject';
        $Storage = new \SprayFire\Structure\Map\RestrictedMap($Type);

        $Storage->setObject($key, $Object);
    }

    public function testGettingNonexistentKey() {
        $key = 'noexist';
        $Type = '\\SprayFire\\Core\\Object';
        $Storage = new \SprayFire\Structure\Map\RestrictedMap($Type);

        $Storage->setObject('i-do-exist', new \SprayFire\Test\Helpers\TestObject());
        $Noexist = $Storage->getObject($key);
        $this->assertNull($Noexist);
        $this->assertSame($Storage->count(), 1);

    }
}
