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

        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', new \stdClass);
        $TestFactory->storeBlueprint($classOne, $blueprintOne);
        $TestFactory->storeBlueprint($classTwo, $blueprintTwo);
        $TestFactory->storeBlueprint($classThree, $blueprintThree);

        $this->assertSame($blueprintOne, $TestFactory->getBlueprint($classOne));
        $this->assertSame($blueprintTwo, $TestFactory->getBlueprint($classTwo));
        $this->assertSame($blueprintThree, $TestFactory->getBlueprint($classThree));
    }

    public function testGettingEmptyBlueprint() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', new \stdClass);
        $this->assertSame(array(), $TestFactory->getBlueprint('SprayFire.NonExistent.Class'));
    }

    public function testGettingFinalBlueprintWithNoOptions() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', new \stdClass);

        $className = 'SprayFire.Test.Class';
        $defaultBlueprint = array('arg1', 'arg2');

        $TestFactory->storeBlueprint($className, $defaultBlueprint);

        $specificOptions = array();
        $expected = array('arg1', 'arg2');
        $this->assertSame($expected, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintReplacingAllDefaultOptions() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', new \stdClass);

        $className = 'SprayFire.Test.Class';
        $defaultBlueprint = array('arg1', 'arg2');

        $TestFactory->storeBlueprint($className, $defaultBlueprint);

        $specificOptions = array('arg3', 'arg4');
        $this->assertSame($specificOptions, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintWithNoStoredBlueprint() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', new \stdClass);
        $className = 'SprayFire.Test.Class';
        $specificOptions = array('arg1', 'arg2');
        $this->assertSame($specificOptions, $TestFactory->testGetFinalBlueprint($className, $specificOptions));
    }

    public function testGettingFinalBlueprintWithMoreOptionsThenDefaultBlueprint() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', new \stdClass);
        $className = 'SprayFire.Test.Class';
        $TestFactory->storeBlueprint($className, array(1, 2));
        $specificOptions = array(null, null, 3, 4, 5);
        $expected = array(1, 2, 3, 4, 5);
        $actual = $TestFactory->testGetFinalBlueprint($className, $specificOptions);
        $this->assertSame($expected, $actual);
    }

    public function testCreatingATestObject() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Test.Helpers.TestObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestObject';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \SprayFire\Test\Helpers\TestObject);
    }

    public function testCreatingATestObjectWithDefaultBlueprintOnly() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.CoreObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestFactoryObject';
        $defaultBlueprint = array('param1', 'param2');
        $TestFactory->storeBlueprint($className, $defaultBlueprint);
        $Object = $TestFactory->makeObject($className);
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\TestFactoryObject', $Object);
        $this->assertSame($defaultBlueprint, $Object->passedParams);
    }

    public function testPassingInvalidNullPrototype() {
        $exceptionThrown = false;
        try {
            $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
            $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
            $ReflectionCache = new \Artax\ReflectionCacher();
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'SprayFire.NonExistent');
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testPassingInvalidReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();

        $this->setExpectedException('\\SprayFire\\Exception\\TypeNotFoundException');
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.NonExistent', 'stdClass');
    }

    public function testCreatingObjectNotProperReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFire.Test.Helpers.TestObject');
        $this->assertTrue($Object instanceof \stdClass);
        $expected = array(
            array(
                'message' => 'The requested object, \\SprayFire\\Test\\Helpers\\TestObject, does not properly implement the appropriate type, \\stdClass, for this factory.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testCreatingObjectNotFound() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFire.NonExistent');
        $this->assertTrue($Object instanceof \stdClass);
        $expected = array(
            array(
                'message' => 'There was an error creating the requested object, \\SprayFire\\NonExistent.  It likely does not exist.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testGettingObjectName() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Object', $Factory->getObjectType());
    }

    public function testDeletingBlueprint() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $ReflectionCache = new \Artax\ReflectionCacher();
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.CoreObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestFactoryObject';
        $defaultBlueprint = array('param1', 'param2');
        $TestFactory->storeBlueprint($className, $defaultBlueprint);
        $this->assertTrue($TestFactory->deleteBlueprint($className));
    }

}