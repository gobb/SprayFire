<?php

use Artax\Provider,
    Artax\ReflectionCacher;

class ProviderTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     */
    public function testMakeInjectsSimpleConcreteDeps() {
    
        $dp = new Provider(new ReflectionCacher);
        $this->assertEquals(new TestNeedsDep(new TestDependency),
            $dp->make('TestNeedsDep')
        );
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     */
    public function testMakePassesNullIfDefaultAndNoTypehintExists() {
    
        $dp = new Provider(new ReflectionCacher);
        $nullCtorParamObj = $dp->make('ProvTestNoDefinitionNullDefaultClass');
        $this->assertEquals(new ProvTestNoDefinitionNullDefaultClass, $nullCtorParamObj);
        $this->assertEquals(NULL, $nullCtorParamObj->arg);
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     */
    public function testMakeReturnsSharedInstanceIfSpecified() {
    
        $dp = new Provider(new ReflectionCacher);
        $dp->define('RequiresInterface', array('dep' => 'DepImplementation'));
        $dp->share('RequiresInterface');
        $injected = $dp->make('RequiresInterface');
        
        $this->assertEquals('something', $injected->testDep->testProp);
        $injected->testDep->testProp = 'something else';
        
        $injected2 = $dp->make('RequiresInterface');
        $this->assertEquals('something else', $injected2->testDep->testProp);
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     * @expectedException Artax\ProviderDefinitionException
     */
    public function testMakeThrowsExceptionOnNonNullScalarTypehintSansDefinitions() {
    
        $dp = new Provider(new ReflectionCacher);
        $dp->make('TestClassWithNoCtorTypehints');
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     * @expectedException Artax\ProviderDefinitionException
     */
    public function testMakeThrowsExceptionIfProvisioningMissingUnloadableClass() {
    
        $dp = new Provider(new ReflectionCacher);
        $dp->make('ClassThatDoesntExist');
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     */
    public function testMakeUsesInstanceDefinitionParamIfSpecified() {
    
        $dp = new Provider(new ReflectionCacher);
        $dp->make('TestMultiDepsNeeded', array('TestDependency', new TestDependency2));
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     */
    public function testMakeUsesCustomDefinitionIfSpecified() {
    
        $dp = new Provider(new ReflectionCacher);
        $dp->define('TestNeedsDep', array('testDep'=>'TestDependency'));
        $injected = $dp->make('TestNeedsDep', array('testDep'=>'TestDependency2'));
        $this->assertEquals('testVal2', $injected->testDep->testProp);
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     * @expectedException Artax\ProviderDefinitionException
     */
    public function testMakeThrowsExceptionOnScalarDefaultCtorParam() {
    
        $dp  = new Provider(new ReflectionCacher);
        $obj = $dp->make('NoTypehintNullDefaultConstructorClass');
    }
    
    /**
     * @covers Artax\Provider::make
     */
    public function testMakeStoresShareIfMarkedWithNullInstance() {
    
        $dp = new Provider(new ReflectionCacher);
        $dp->share('TestDependency');
        $dp->make('TestDependency');
        $this->assertTrue($dp->isShared('TestDependency'));
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     */
    public function testMakeUsesReflectionForUnknownParamsInMultiBuildWithDeps() {
    
        $dp  = new Provider(new ReflectionCacher);
        $obj = $dp->make('TestMultiDepsWithCtor', array('val1'=>'TestDependency'));
        $this->assertInstanceOf('TestMultiDepsWithCtor', $obj);
        
        $obj = $dp->make('NoTypehintNoDefaultConstructorClass',
            array('val1'=>'TestDependency')
        );
        $this->assertInstanceOf('NoTypehintNoDefaultConstructorClass', $obj);
        $this->assertEquals(NULL, $obj->testParam);
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     * @expectedException Artax\ProviderDefinitionException
     */
    public function testThrowsExceptionOnUnknownParamsInMultiBuildWithDeps() {
    
        $dp  = new Provider(new ReflectionCacher);
        $obj = $dp->make('NoTypehintNullDefaultConstructorClass',
            array('val1'=>'TestDependency')
        );
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::getInjectedInstance
     * @covers Artax\Provider::buildNewInstanceArgs
     * @covers Artax\Provider::isInstantiable
     * @expectedException Artax\ProviderDefinitionException
     */
    public function testThrowsExceptionOnUninstantiableTypehintWithoutDefinition() {
    
        $dp  = new Provider(new ReflectionCacher);
        $obj = $dp->make('RequiresInterface');
    }
    
    /**
     * @covers Artax\Provider::define
     */
    public function testDefineAssignsPassedDefinition() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->define('RequiresInterface', array('dep' => 'DepImplementation'));
        $this->assertInstanceOf('RequiresInterface', $dp->make('RequiresInterface'));
    }
    
    /**
     * @covers Artax\Provider::defineAll
     * @expectedException InvalidArgumentException
     */
    public function testDefineAllThrowsExceptionOnInvalidIterable() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->defineAll(1);
    }
    
    /**
     * @covers Artax\Provider::defineAll
     */
    public function testDefineAllAssignsPassedDefinitionsAndReturnsAddedCount() {
        
        $dp = new Provider(new ReflectionCacher);
        $depList = array();
        $depList['RequiresInterface'] = array('dep' => 'DepImplementation');
        
        $this->assertEquals(1, $dp->defineAll($depList));
        $this->assertInstanceOf('RequiresInterface', $dp->make('RequiresInterface'));
    }
    
    /**
     * @covers Artax\Provider::remove
     */
    public function testRemoveClearsDefinitionAndReturnsProvider() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->define('RequiresInterface', array('dep' => 'DepImplementation'));
        $this->assertTrue($dp->isDefined('RequiresInterface'));
        $this->assertEquals($dp, $dp->remove('RequiresInterface'));
        $this->assertFalse($dp->isDefined('RequiresInterface'));
    }
    
    /**
     * @covers Artax\Provider::removeAll
     */
    public function testRemoveAllClearsDefinitionAndReturnsProvider() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->define('RequiresInterface', array('dep' => 'DepImplementation'));
        $this->assertTrue($dp->isDefined('RequiresInterface'));
        
        $return = $dp->removeAll();
        $this->assertEquals($dp, $dp->removeAll());
        $this->assertFalse($dp->isDefined('RequiresInterface'));
    }
    
    /**
     * @covers Artax\Provider::refresh
     */
    public function testRefreshClearsSharedInstancesAndReturnsProvider() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->share('TestDependency');
        $obj = $dp->make('TestDependency');
        $this->assertTrue($dp->isShared('TestDependency'));
        $obj->testProp = 42;
        
        $this->assertEquals($dp, $dp->refresh('TestDependency'));
        $refreshedObj = $dp->make('TestDependency');
        $this->assertEquals('testVal', $refreshedObj->testProp);
    }
    
    /**
     * @covers Artax\Provider::isShared
     */
    public function testIsSharedReturnsSharedStatus() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->share('TestDependency');
        $this->assertTrue($dp->isShared('TestDependency'));
    }
    
    /**
     * @covers Artax\Provider::unshare
     */
    public function testUnsharedReturnsBool() { 
    
        $dp = new Provider(new ReflectionCacher);
        $this->assertFalse($dp->isShared('TestDependency'));
        $dp->share('TestDependency');
        $this->assertTrue($dp->isShared('TestDependency'));
        $this->assertEquals($dp, $dp->unshare('TestDependency'));
        $this->assertFalse($dp->isShared('TestDependency'));
    }
    
    /**
     * @covers Artax\Provider::isDefined
     */
    public function testIsDefinedReturnsDefinedStatus() {
    
        $dp = new Provider(new ReflectionCacher);
        $this->assertFalse($dp->isDefined('RequiresInterface'));
        $dp->define('RequiresInterface', array('dep' => 'DepImplementation'));
        
        $this->assertTrue($dp->isDefined('RequiresInterface'));
    }
    
    /**
     * @covers Artax\Provider::share
     */
    public function testShareStoresSharedDependencyAndReturnsChainableInstance() {
        
        $dp = new Provider(new ReflectionCacher);
        $testShare = new StdClass;
        $testShare->test = 42;
        
        $this->assertEquals($dp, $dp->share('StdClass', $testShare));
        $testShare->test = 'test';
        $this->assertEquals('test', $dp->make('stdclass')->test);
        
    }
    
    /**
     * @covers Artax\Provider::share
     */
    public function testShareMarksClassSharedOnNoObjectParameter() {
        
        $dp = new Provider(new ReflectionCacher);
        $this->assertEquals($dp, $dp->share('Artax\\Mediator'));
        $this->assertTrue($dp->isShared('Artax\Mediator'));
    }
    
    /**
     * @covers Artax\Provider::share
     * @expectedException InvalidArgumentException
     */
    public function testShareThrowsExceptionOnInvalidArgument() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->share('Artax\\Mediator', new StdClass);
    }
    
    /**
     * @covers Artax\Provider::setImplementation
     * @covers Artax\Provider::getImplementation
     * @covers Artax\Provider::hasImplementation
     */
    public function testSetImplementationAssignsValueAndReturnsProviderInstance() {
        
        $dp = new Provider(new ReflectionCacher);
        $this->assertEquals($dp, $dp->setImplementation('DepInterface', 'DepImplementation'));
        $this->assertTrue($dp->hasImplementation('DepInterface'));
        $this->assertEquals('DepImplementation', $dp->getImplementation('DepInterface'));
    }
    
    /**
     * @covers Artax\Provider::clearImplementation
     * @covers Artax\Provider::hasImplementation
     */
    public function testClearImplementationRemovesAssignedValueAndReturnsProviderInstance() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->setImplementation('DepInterface', 'DepImplementation');
        $this->assertTrue($dp->hasImplementation('DepInterface'));
        $dp->clearImplementation('DepInterface');
        $this->assertFalse($dp->hasImplementation('DepInterface'));
    }
    
    /**
     * @covers Artax\Provider::getImplementation
     * @expectedException OutOfBoundsException
     */
    public function testGetImplementationThrowsExceptionOnInvalidNonConcreteParam() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->getImplementation('InterfaceThatIsNotSetWithAnImplementation');
    }
    
    /**
     * @covers Artax\Provider::make
     * @covers Artax\Provider::buildNewInstanceArgs
     */
    public function testMakeUsesImplementationDefinitionsAsNeeded() {
        
        $dp = new Provider(new ReflectionCacher);
        $dp->setImplementation('DepInterface', 'DepImplementation');
        $this->assertInstanceOf('RequiresInterface', $dp->make('RequiresInterface'));
    }
}

class TestDependency {
    public $testProp = 'testVal';
}

class TestDependency2 extends TestDependency {
    public $testProp = 'testVal2';
}

class SpecdTestDependency extends TestDependency {
    public $testProp = 'testVal';
}

class TestNeedsDep {
    public function __construct(TestDependency $testDep) {
        $this->testDep = $testDep;
    }
}

class TestClassWithNoCtorTypehints {
    public function __construct($val = 42) {
        $this->test = $val;
    }
}

class TestMultiDepsNeeded {
    public function __construct(TestDependency $val1, TestDependency2 $val2) {
        $this->testDep = $val1;
        $this->testDep = $val2;
    }
}


class TestMultiDepsWithCtor {
    public function __construct(TestDependency $val1, TestNeedsDep $val2) {
        $this->testDep = $val1;
        $this->testDep = $val2;
    }
}

class NoTypehintNullDefaultConstructorClass {
    public $testParam = 1;
    public function __construct(TestDependency $val1, $arg=42) {
        $this->testParam = $arg;
    }
}

class NoTypehintNoDefaultConstructorClass {
    public $testParam = 1;
    public function __construct(TestDependency $val1, $arg = NULL) {
        $this->testParam = $arg;
    }
}

interface DepInterface {}

class DepImplementation implements DepInterface {
    public $testProp = 'something';
}

class RequiresInterface {
    public $dep;
    public function __construct(DepInterface $dep) {
        $this->testDep = $dep;
    }
}

class ProvTestNoDefinitionNullDefaultClass {
    public function __construct($arg = NULL) {
        $this->arg = $arg;
    }
}
