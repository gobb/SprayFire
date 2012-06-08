<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Factory;

/**
 * @brief
 */
class BaseFactoryTest extends \PHPUnit_Framework_TestCase {

    public function testStoringAndGettingBlueprints() {
        $classOne = 'SprayFire.Class.One';
        $classTwo = 'SprayFire.Class.Two';
        $classThree = 'SprayFire.Class.Three';
        $blueprintOne = array(1);
        $blueprintTwo = array('1', '2');
        $blueprintThree = array('one', 'two', 'three');


        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', new \stdClass);
        $TestFactory->storeBlueprint($classOne, $blueprintOne);
        $TestFactory->storeBlueprint($classTwo, $blueprintTwo);
        $TestFactory->storeBlueprint($classThree, $blueprintThree);

        $this->assertSame($blueprintOne, $TestFactory->getBlueprint($classOne));
        $this->assertSame($blueprintTwo, $TestFactory->getBlueprint($classTwo));
        $this->assertSame($blueprintThree, $TestFactory->getBlueprint($classThree));
    }

    public function testGettingEmptyBlueprint() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', new \stdClass);
        $this->assertSame(array(), $TestFactory->getBlueprint('SprayFire.NonExistent.Class'));
    }

    public function testGettingFinalBlueprintWithNoOptions() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', new \stdClass);

        $className = 'SprayFire.Test.Class';
        $defaultBlueprint = array('arg1', 'arg2');

        $TestFactory->storeBlueprint($className, $defaultBlueprint);

        $specificOptions = array();
        $expected = array('arg1', 'arg2');
        $this->assertSame($expected, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintReplacingAllDefaultOptions() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', new \stdClass);

        $className = 'SprayFire.Test.Class';
        $defaultBlueprint = array('arg1', 'arg2');

        $TestFactory->storeBlueprint($className, $defaultBlueprint);

        $specificOptions = array('arg3', 'arg4');
        $this->assertSame($specificOptions, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintWithNoStoredBlueprint() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', new \stdClass);
        $className = 'SprayFire.Test.Class';
        $specificOptions = array('arg1', 'arg2');
        $this->assertSame($specificOptions, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintWithMoreOptionsThenDefaultBlueprint() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', new \stdClass);
        $className = 'SprayFire.Test.Class';
        $TestFactory->storeBlueprint($className, array(1, 2));
        $specificOptions = array(null, null, 3, 4, 5);
        $expected = array(1, 2, 3, 4, 5);
        $actual = $TestFactory->testGetFinalBlueprint($className, $specificOptions);
        $this->assertSame($expected, $actual);
    }

    public function testCreatingATestObject() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'SprayFire.Test.Helpers.TestObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestObject';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \SprayFire\Test\Helpers\TestObject);
    }

    public function testCreatingATestObjectWithDefaultBlueprintOnly() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'SprayFire.Util.CoreObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestFactoryObject';
        $defaultBlueprint = array('param1', 'param2');
        $TestFactory->storeBlueprint($className, $defaultBlueprint);
        $Object = $TestFactory->makeObject($className);
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\TestFactoryObject', $Object);
        $this->assertSame($defaultBlueprint, $Object->passedParams);
    }

    public function testGettingANullObjectOnErrorPassingStringNameAsNullPrototype() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', 'stdClass');
        $className = 'SprayFire.Test.Class';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \stdClass);
    }

    public function testPassingInvalidNullPrototype() {
        $exceptionThrown = false;
        try {
            $ReflectionCache = new \Artax\ReflectionCache();
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', 'SprayFire.NonExistent');
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testPassingInvalidReturnType() {
        $exceptionThrown = false;
        try {
            $ReflectionCache = new \Artax\ReflectionCache();
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'SprayFire.NonExistent', 'stdClass');
        } catch (\SprayFire\Exception\TypeNotFoundException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testCreatingObjectNotProperReturnType() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFire.Test.Helpers.TestObject');
        $this->assertTrue($Object instanceof \stdClass);
    }

    public function testGettingObjectName() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Object', $Factory->getObjectType());
    }

    public function testDeletingBlueprint() {
        $ReflectionCache = new \Artax\ReflectionCache();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, 'SprayFire.Util.CoreObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestFactoryObject';
        $defaultBlueprint = array('param1', 'param2');
        $TestFactory->storeBlueprint($className, $defaultBlueprint);
        $this->assertTrue($TestFactory->deleteBlueprint($className));
    }

}