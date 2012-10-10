<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Factory\FireFactory;

/**
 * @package SprayFireTest
 * @subpackage Cases.Factory.FireFactory
 */
class BaseTest extends \PHPUnit_Framework_TestCase {

    public function testCreatingATestObject() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Test.Helpers.TestObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestObject';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \SprayFire\Test\Helpers\TestObject, 'The object is ' . \get_class($Object) . ' and not a SprayFire.Test.Helpers.TestObject');
    }

    public function testPassingInvalidNullPrototype() {
        $exceptionThrown = false;
        try {
            $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
            $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
            $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
            $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'SprayFire.NonExistent');
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testPassingInvalidReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $this->setExpectedException('\\InvalidArgumentException');
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.NonExistent', 'stdClass');
    }

    public function testCreatingObjectNotProperReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFire.Test.Helpers.TestObject');
        $this->assertTrue($Object instanceof \stdClass);
        $expected = array(
            array(
                'message' => 'The requested object, SprayFire.Test.Helpers.TestObject, does not properly implement the appropriate type, \\stdClass, for this factory.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testReturningNullObjectIfResourceNotFound() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestObject');
        $Object = $Factory->makeObject('SprayFire.NonExistent');
        $this->assertTrue($Object instanceof \SprayFire\Test\Helpers\TestObject, 'The object returns is not the appropriate NullObject');
        $expected = array(
            array(
                'message' => 'There was an error creating the requested object, SprayFire.NonExistent.  It likely does not exist.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testGettingObjectName() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Object', $Factory->getObjectType());
    }

    public function testGettingNullObjectName() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Test\\Helpers\\TestFactoryObject', $Factory->getNullObjectType());
    }

}