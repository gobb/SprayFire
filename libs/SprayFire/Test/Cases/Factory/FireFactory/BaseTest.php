<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Factory\FireFactory;

/**
 * @brief
 */
class BaseTest extends \PHPUnit_Framework_TestCase {

    public function testCreatingATestObject() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $TestFactory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Test.Helpers.TestObject', new \SprayFire\Test\Helpers\TestObject());
        $className = 'SprayFire.Test.Helpers.TestObject';
        $Object = $TestFactory->makeObject($className);
        $this->assertTrue($Object instanceof \SprayFire\Test\Helpers\TestObject, 'The object is ' . \get_class($Object) . ' and not a SprayFire.Test.Helpers.TestObject');
    }

    public function testPassingInvalidNullPrototype() {
        $exceptionThrown = false;
        try {
            $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
            $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
            $JavaConverter = new \SprayFire\JavaNamespaceConverter();
            $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
            $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'SprayFire.NonExistent');
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testPassingInvalidReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $this->setExpectedException('\\SprayFire\\Factory\\Exception\\TypeNotFound');
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.NonExistent', 'stdClass');
    }

    public function testCreatingObjectNotProperReturnType() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'stdClass', 'stdClass');
        $Object = $Factory->makeObject('SprayFire.Test.Helpers.TestObject');
        $this->assertTrue($Object instanceof \stdClass);
        $expected = array(
            array(
                'message' => 'The requested object, SprayFire.Test.Helpers.TestObject, does not properly implement the appropriate type, stdClass, for this factory.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testReturningNullObjectIfResourceNotFound() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestObject');
        $Factory->setErrorHandlingMethod(\SprayFire\Factory\FireFactory\Base::RETURN_NULL_OBJECT); // this is done by default, testing setting option explicitly
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

    public function testThrowingExceptionIfResourceNotFound() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestObject');
        $Factory->setErrorHandlingMethod(\SprayFire\Factory\FireFactory\Base::THROW_EXCEPTION);
        $this->setExpectedException('\\SprayFire\\Factory\\Exception\\TypeNotFound');
        $Factory->makeObject('SprayFire.NonExistent');
    }

    public function testGettingObjectName() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Object', $Factory->getObjectType());
    }

    public function testGettingNullObjectName() {
        $EmergencyLogger = $ErrorLogger = $DebugLogger = $InfoLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $JavaConverter = new \SprayFire\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\ReflectionCache($JavaConverter);
        $Factory = new \SprayFire\Test\Helpers\TestBaseFactory($ReflectionCache, $LogDelegator, 'SprayFire.Object', 'SprayFire.Test.Helpers.TestFactoryObject');
        $this->assertSame('\\SprayFire\\Test\\Helpers\\TestFactoryObject', $Factory->getNullObjectType());
    }

}