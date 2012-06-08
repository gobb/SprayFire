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


        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', new \stdClass);
        $TestFactory->storeBlueprint($classOne, $blueprintOne);
        $TestFactory->storeBlueprint($classTwo, $blueprintTwo);
        $TestFactory->storeBlueprint($classThree, $blueprintThree);

        $this->assertSame($blueprintOne, $TestFactory->getBlueprint($classOne));
        $this->assertSame($blueprintTwo, $TestFactory->getBlueprint($classTwo));
        $this->assertSame($blueprintThree, $TestFactory->getBlueprint($classThree));
    }

    public function testGettingEmptyBlueprint() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', new \stdClass);
        $this->assertSame(array(), $TestFactory->getBlueprint('SprayFire.NonExistent.Class'));
    }

    public function testGettingFinalBlueprintWithNoOptions() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', new \stdClass);

        $className = 'SprayFire.Test.Class';
        $defaultBlueprint = array('arg1', 'arg2');

        $TestFactory->storeBlueprint($className, $defaultBlueprint);

        $specificOptions = array();
        $expected = array('arg1', 'arg2');
        $this->assertSame($expected, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintReplacingAllDefaultOptions() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', new \stdClass);

        $className = 'SprayFire.Test.Class';
        $defaultBlueprint = array('arg1', 'arg2');

        $TestFactory->storeBlueprint($className, $defaultBlueprint);

        $specificOptions = array('arg3', 'arg4');
        $this->assertSame($specificOptions, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintWithNoStoredBlueprint() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', new \stdClass);
        $className = 'SprayFire.Test.Class';
        $specificOptions = array('arg1', 'arg2');
        $this->assertSame($specificOptions, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintWithMoreOptionsThenDefaultBlueprint() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', new \stdClass);
        $className = 'SprayFire.Test.Class';
        $TestFactory->storeBlueprint($className, array(1, 2));
        $specificOptions = array(null, null, 3, 4, 5);
        $expected = array(1, 2, 3, 4, 5);
        $actual = $TestFactory->testGetFinalBlueprint($className, $specificOptions);
        $this->assertSame($expected, $actual);
    }

    public function testCreatingATestObject() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'SprayFire.Test.Helpers.TestObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestObject';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \SprayFire\Test\Helpers\TestObject);
    }

    public function testCreatingATestObjectWithDefaultBlueprintOnly() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'SprayFire.Util.CoreObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestFactoryObject';
        $defaultBlueprint = array('param1', 'param2');
        $TestFactory->storeBlueprint($className, $defaultBlueprint);
        $Object = $TestFactory->makeObject($className);
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\TestFactoryObject', $Object);
        $this->assertSame($defaultBlueprint, $Object->passedParams);
    }

    public function testGettingANullObjectOnErrorPassingStringNameAsNullPrototype() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', 'stdClass');
        $className = 'SprayFire.Test.Class';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \stdClass);
    }

    public function testPassingInvalidNullPrototype() {
        $exceptionThrown = false;
        try {
            $ReflectionPool = new \Artax\ReflectionPool();
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', 'SprayFire.NonExistent');
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testPassingInvalidReturnType() {
        $exceptionThrown = false;
        try {
            $ReflectionPool = new \Artax\ReflectionPool();
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'SprayFire.NonExistent', 'stdClass');
        } catch (\SprayFire\Exception\TypeNotFoundException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testCreatingObjectNotProperReturnType() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFire.Test.Helpers.TestObject');
        $this->assertTrue($Object instanceof \stdClass);
    }

    public function testGettingObjectName() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Object', $Factory->getObjectType());
    }

    public function testDeletingBlueprint() {
        $ReflectionPool = new \Artax\ReflectionPool();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionPool, 'SprayFire.Util.CoreObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestFactoryObject';
        $defaultBlueprint = array('param1', 'param2');
        $TestFactory->storeBlueprint($className, $defaultBlueprint);
        $this->assertTrue($TestFactory->deleteBlueprint($className));
    }

}