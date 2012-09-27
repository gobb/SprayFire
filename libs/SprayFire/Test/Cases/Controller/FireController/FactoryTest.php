<?php

/**
 * Test the Controller Factory to ensure that Controller objects are created and
 * setup properly.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this code
 */

namespace SprayFire\Test\Cases\Controller\FireController;

use \SprayFire\Service\FireService\Container as Container,
    \SprayFire\Utils as SFUtils,
    \SprayFire\Controller\FireController\Factory as Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase {

    protected $ReflectionCache;

    protected $Container;

    protected $JavaNameConverter;

    protected $ErrorLogger;

    public function setUp() {
        $this->JavaNameConverter = new SFUtils\JavaNamespaceConverter();
        $this->ReflectionCache = new SFUtils\ReflectionCache($this->JavaNameConverter);
        $this->Container = new Container($this->ReflectionCache);
        $this->ErrorLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
    }


    public function testFactoryWithValidServicesInController() {
        $this->Container->addService('SprayFire.Test.Helpers.Controller.ServiceOne', null);
        $this->Container->addService('SprayFire.Test.Helpers.Controller.ServiceTwo', null);
        $Factory = $this->getFactory();

        $Controller = $Factory->makeObject('SprayFire.Test.Helpers.Controller.ValidServices');
        $this->assertInstanceOf('\\SprayFire\\Controller\\Controller', $Controller);
        $serviceOneType = '\\SprayFire\\Test\\Helpers\\Controller\\ServiceOne';
        $serviceTwoType = '\\SprayFire\\Test\\Helpers\\Controller\\ServiceTwo';
        $this->assertInstanceOf($serviceOneType, $Controller->service('ServiceOne'));
        $this->assertInstanceOf($serviceTwoType, $Controller->service('ServiceTwo'));
    }

    public function testFactoryGettingControllerDoesNotExist() {
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObject';
        $Factory = $this->getFactory($type, $nullType);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullObject', $Controller);
    }

    public function testFactoryGettingNullObjectWithValidServices() {
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObjectWithServices';
        $Factory = $this->getFactory($type, $nullType);

        $this->Container->addService('SprayFire.Test.Helpers.Controller.NullServiceOne', null);
        $this->Container->addService('SprayFire.Test.Helpers.Controller.NullServiceTwo', null);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullObjectWithServices', $Controller);
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullServiceOne', $Controller->service('ServiceOne'));

        $expected = array(
            array(
                'message' => 'There was an error creating the requested object, SprayFire.Controller.NoGo.  It likely does not exist.',
                'options' => array()
            )
        );
        $actual = $this->ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testFactoryGettingNullObjectWithInvalidServices() {
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObjectInvalidServices';
        $Factory = $this->getFactory($type, $nullType);

        $this->setExpectedException('\\SprayFire\\Service\\NotFoundException');
        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
    }

    protected function getFactory($type = 'SprayFire.Controller.Controller', $nullType = 'SprayFire.Controller.NullObject') {
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\NullLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $this->ErrorLogger, $DebugLogger, $InfoLogger);
        return new Factory($this->ReflectionCache, $this->Container, $LogDelegator, $type, $nullType);
    }

}
