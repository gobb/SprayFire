<?php

/**
 * Test the Controller Factory to ensure that Controller objects are created and
 * setup properly.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this code
 */

namespace SprayFireTest\Controller\FireController;

use \SprayFireTest\Helpers as FireTestHelpers,
    \SprayFire\StdLib as SFStdLib,
    \SprayFire\Service\FireService as FireService,
    \SprayFire\Controller\FireController as FireController,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Controller.FireController
 */
class FactoryTest extends PHPUnitTestCase {

    protected $ReflectionCache;

    protected $Container;

    protected $JavaNameConverter;

    protected $ErrorLogger;

    public function setUp() {
        $this->JavaNameConverter = new SFStdLib\JavaNamespaceConverter();
        $this->ReflectionCache = new SFStdLib\ReflectionCache($this->JavaNameConverter);
        $this->Container = new FireService\Container($this->ReflectionCache);
        $this->ErrorLogger = new FireTestHelpers\DevelopmentLogger();
    }


    public function testFactoryWithValidServicesInController() {
        $this->Container->addService('SprayFireTest.Helpers.Controller.ServiceOne', null);
        $this->Container->addService('SprayFireTest.Helpers.Controller.ServiceTwo', null);
        $Factory = $this->getFactory();

        $Controller = $Factory->makeObject('SprayFireTest.Helpers.Controller.ValidServices');
        $this->assertInstanceOf('\\SprayFire\\Controller\\Controller', $Controller);
        $serviceOneType = '\\SprayFireTest\\Helpers\\Controller\\ServiceOne';
        $serviceTwoType = '\\SprayFireTest\\Helpers\\Controller\\ServiceTwo';
        $this->assertInstanceOf($serviceOneType, $Controller->service('ServiceOne'));
        $this->assertInstanceOf($serviceTwoType, $Controller->service('ServiceTwo'));
    }

    public function testFactoryGettingControllerDoesNotExist() {
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFireTest.Helpers.Controller.NullObject';
        $Factory = $this->getFactory($type, $nullType);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFireTest\\Helpers\\Controller\\NullObject', $Controller);
    }

    public function testFactoryGettingNullObjectWithValidServices() {
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFireTest.Helpers.Controller.NullObjectWithServices';
        $Factory = $this->getFactory($type, $nullType);

        $this->Container->addService('SprayFireTest.Helpers.Controller.NullServiceOne', null);
        $this->Container->addService('SprayFireTest.Helpers.Controller.NullServiceTwo', null);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFireTest\\Helpers\\Controller\\NullObjectWithServices', $Controller);
        $this->assertInstanceOf('\\SprayFireTest\\Helpers\\Controller\\NullServiceOne', $Controller->service('ServiceOne'));

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
        $nullType = 'SprayFireTest.Helpers.Controller.NullObjectInvalidServices';
        $Factory = $this->getFactory($type, $nullType);

        $this->setExpectedException('\\SprayFire\\Service\\Exception\\ServiceNotFound');
        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
    }

    protected function getFactory($type = 'SprayFire.Controller.Controller', $nullType = 'SprayFire.Controller.NullObject') {
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\NullLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $this->ErrorLogger, $DebugLogger, $InfoLogger);
        return new FireController\Factory($this->ReflectionCache, $this->Container, $LogDelegator, $type, $nullType);
    }

}
