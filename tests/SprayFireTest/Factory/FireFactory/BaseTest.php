<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Factory\FireFactory;

use \SprayFireTest\Helpers as FireTestHelpers,
    \SprayFire\Logging\FireLogging as FireLogging,
    \SprayFire\Utils as SFUtils,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Factory.FireFactory
 */
class BaseTest extends PHPUnitTestCase {

    public function testCreatingATestObject() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new SFUtils\JavaNamespaceConverter();
        $ReflectionCache = new SFUtils\ReflectionCache($JavaConverter);
        $TestFactory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFireTest.Helpers.TestObject', new FireTestHelpers\TestObject());
        $className = 'SprayFireTest.Helpers.TestObject';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof FireTestHelpers\TestObject, 'The object is ' . \get_class($Object) . ' and not a SprayFireTest.Helpers.TestObject');
    }

    public function testPassingInvalidNullPrototype() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new SFUtils\JavaNamespaceConverter();
        $ReflectionCache = new SFUtils\ReflectionCache($JavaConverter);

        $this->setExpectedException('\\SprayFire\\Factory\\Exception\\TypeNotFound');
        $Factory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'SprayFire.NonExistent');
    }

    public function testPassingInvalidReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new SFUtils\JavaNamespaceConverter();
        $ReflectionCache = new SFUtils\ReflectionCache($JavaConverter);
        $this->setExpectedException('\\SprayFire\\Factory\\Exception\\TypeNotFound');
        $Factory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.NonExistent', 'stdClass');
    }

    public function testCreatingObjectNotProperReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaConverter);
        $Factory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFireTest.Helpers.TestObject');
        $this->assertTrue($Object instanceof \stdClass);
        $expected = array(
            array(
                'message' => 'The requested object, SprayFireTest.Helpers.TestObject, does not properly implement the appropriate type, \\stdClass, for this factory.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testReturningNullObjectIfResourceNotFound() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new SFUtils\JavaNamespaceConverter();
        $ReflectionCache = new SFUtils\ReflectionCache($JavaConverter);
        $Factory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFireTest.Helpers.TestObject');
        $Object = $Factory->makeObject('SprayFire.NonExistent');
        $this->assertTrue($Object instanceof FireTestHelpers\TestObject, 'The object returns is not the appropriate NullObject');
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
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new SFUtils\JavaNamespaceConverter();
        $ReflectionCache = new SFUtils\ReflectionCache($JavaConverter);
        $Factory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFireTest.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Object', $Factory->getObjectType());
    }

    public function testGettingNullObjectName() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new FireTestHelpers\DevelopmentLogger();
        $LogDelegator = new FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new SFUtils\JavaNamespaceConverter();
        $ReflectionCache = new SFUtils\ReflectionCache($JavaConverter);
        $Factory = new FireTestHelpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFireTest.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFireTest\\Helpers\\TestFactoryObject', $Factory->getNullObjectType());
    }

}
