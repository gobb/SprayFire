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
        $Factory = new Factory($ReflectionCache, $Container);

        $Container->addService('SprayFire.Test.Helpers.Controller.ServiceOne', null);
        $Container->addService('SprayFire.Test.Helpers.Controller.ServiceTwo', null);

        $Controller = $Factory->makeObject('SprayFire.Test.Helpers.Controller.ValidServices');
        $this->assertInstanceOf('\\SprayFire\\Controller\\Controller', $Controller);
        $serviceOneType = '\\SprayFire\\Test\\Helpers\\Controller\\ServiceOne';
        $serviceTwoType = '\\SprayFire\\Test\\Helpers\\Controller\\ServiceTwo';
        $this->assertInstanceOf($serviceOneType, $Controller->ServiceOne);
        $this->assertInstanceOf($serviceTwoType, $Controller->ServiceTwo);
    }

    public function testFactoryGettingControllerDoesNotExist() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObject';
        $Factory = new Factory($ReflectionCache, $Container, $type, $nullType);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullObject', $Controller);
    }

    public function testFactoryGettingNullObjectWithValidServices() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObjectWithServices';
        $Factory = new Factory($ReflectionCache, $Container, $type, $nullType);

        $Container->addService('SprayFire.Test.Helpers.Controller.NullServiceOne', null);
        $Container->addService('SprayFire.Test.Helpers.Controller.NullServiceTwo', null);

        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullObjectWithServices', $Controller);
        $this->assertInstanceOf('\\SprayFire\\Test\\Helpers\\Controller\\NullServiceOne', $Controller->ServiceOne);
    }

    public function testFactoryGettingNullObjectWithInvalidServices() {
        $ReflectionCache = new ReflectionCacher();
        $Container = new Container($ReflectionCache);
        $type = 'SprayFire.Controller.Controller';
        $nullType = 'SprayFire.Test.Helpers.Controller.NullObjectInvalidServices';
        $Factory = new Factory($ReflectionCache, $Container, $type, $nullType);

        $this->setExpectedException('\\SprayFire\\Exception\\FatalRuntimeException');
        $Controller = $Factory->makeObject('SprayFire.Controller.NoGo');
    }

}
