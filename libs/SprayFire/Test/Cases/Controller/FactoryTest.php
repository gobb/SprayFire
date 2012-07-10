<?php

/**
 * Test the Controller Factory to ensure that Controller objects are created and
 * setup properly.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this code
 */

namespace SprayFire\Test\Cases\Controller;

use \SprayFire\Service\FireBox\Container as Container,
    \SprayFire\Controller\Factory as Factory,
    \Artax\ReflectionCacher as ReflectionCacher;

class FactoryTest extends \PHPUnit_Framework_TestCase {

    public function testFactoryWithValidServicesInController() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\Logifier\NullLogger();
        $ErrorLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $Factory = new Factory($ReflectionCache, $Container, $LogDelegator);

        $Container->addService('SprayFire.Test.Helpers.Controller.ServiceOne', null);
        $Container->addService('SprayFire.Test.Helpers.Controller.ServiceTwo', null);

        $Controller = $Factory->makeObject('SprayFire.Test.Helpers.Controller.ValidServices');
        $this->assertInstanceOf('\\SprayFire\\Controller\\Controller', $Controller);
        $serviceOneType = '\\SprayFire\\Test\\Helpers\\Controller\\ServiceOne';
        $serviceTwoType = '\\SprayFire\\Test\\Helpers\\Controller\\ServiceTwo';
        $this->assertInstanceOf($serviceOneType, $Controller->service('ServiceOne'));
        $this->assertInstanceOf($serviceTwoType, $Controller->service('ServiceTwo'));
    }

    public function testFactoryGettingControllerDoesNotExist() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\Logifier\NullLogger();
        $ErrorLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObject';
        $Factory = new Factory($ReflectionCache, $Container, $LogDelegator, $type, $nullType);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullObject', $Controller);
    }

    public function testFactoryGettingNullObjectWithValidServices() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\Logifier\NullLogger();
        $ErrorLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObjectWithServices';
        $Factory = new Factory($ReflectionCache, $Container, $LogDelegator, $type, $nullType);

        $Container->addService('SprayFire.Test.Helpers.Controller.NullServiceOne', null);
        $Container->addService('SprayFire.Test.Helpers.Controller.NullServiceTwo', null);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullObjectWithServices', $Controller);
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullServiceOne', $Controller->service('ServiceOne'));

        $expected = array(
            array(
                'message' => 'The requested controller, SprayFire.Controller.NoGo, could not be found.',
                'options' => array()
            )
        );
        $actual = $ErrorLogger->getLoggedMessages();
        $this->assertSame($expected, $actual);
    }

    public function testFactoryGettingNullObjectWithInvalidServices() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
$EmergencyLogger = $DebugLogger = $InfoLogger = $ErrorLogger = new \SprayFire\Logging\Logifier\NullLogger();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObjectInvalidServices';
        $Factory = new Factory($ReflectionCache, $Container, $LogDelegator, $type, $nullType);

        $this->setExpectedException('\\SprayFire\\Exception\\FatalRuntimeException');
        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
    }

}
