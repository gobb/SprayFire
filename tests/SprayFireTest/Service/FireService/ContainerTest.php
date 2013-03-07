<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of Container
 */

namespace SprayFireTest\Service\FireService;

use \SprayFire\Factory as SFFactory;

/**
 * @package SprayFireTest
 * @subpackage Service.FireService
 */
class ContainerTest extends \PHPUnit_Framework_TestCase {

    protected $ReflectionCache;

    public function testResourceDoesNotExist() {
        $Container = $this->getContainer();
        $directoryExist = $Container->doesServiceExist('SprayFire.FileSys.FireFileSys.Paths');
        $this->assertFalse($directoryExist);
    }

    public function testResourceDoesExist() {
        $Container = $this->getContainer();
        $Container->addService('SprayFire.FileSys.FireFileSys.Paths');
        $directoryExist = $Container->doesServiceExist('SprayFire.FileSys.FireFileSys.Paths');
        $this->assertTrue($directoryExist);
    }

    public function testAddingNotCallableParameters() {
        $Container = $this->getContainer();
        $this->setExpectedException('\\InvalidArgumentException');
        $Container->addService('SprayFire.FileSys.FireFileSys.Paths', array());
    }

    public function testAddingNullParametersDefaultToEmptyCallback() {
        $Container = $this->getContainer();
        $Container->addService('SprayFireTest.Service.FireService.Value', null);
        $Service = $Container->getService('SprayFireTest.Service.FireService.Value');
        $this->assertInstanceOf('\\SprayFireTest\\Service\\FireService\\Value', $Service);
    }

    public function testGettingPathsService() {
        $serviceName = 'SprayFire.FileSys.FireFileSys.Paths';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($install);
            return array($RootPaths);
        };
        $Container = $this->getContainer();
        $Container->addService($serviceName, $parameters);
        $Directory = $Container->getService($serviceName);
        $this->assertInstanceOf('\\SprayFire\\FileSys\\FireFileSys\\Paths', $Directory);
    }

    public function testCachingServices() {
        $serviceName = 'SprayFire.FileSys.FireFileSys.Paths';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($install);
            return array($RootPaths);
        };
        $Container = $this->getContainer();
        $Container->addService($serviceName, $parameters);
        $DirectoryOne = $Container->getService($serviceName);
        $DirectoryTwo = $Container->getService($serviceName);
        $this->assertSame($DirectoryOne, $DirectoryTwo);
    }

    public function testAddingObjectAsServiceAndRetrievingRightObject() {
        $Container = $this->getContainer();
        $Container->addService($this->ReflectionCache, function() {return array();});
        $Cache = $Container->getService('SprayFire.StdLib.ReflectionCache');
        $this->assertSame($this->ReflectionCache, $Cache);
    }

    public function testAddingObjectAsServiceAndCheckingServiceExists() {
        $Container = $this->getContainer();
        $Container->addService($this->ReflectionCache, function() {return array();});
        $this->assertTrue($Container->doesServiceExist('SprayFire.StdLib.ReflectionCache'));
    }

    public function testGettingServiceThatDoesNotExist() {
        $Container = $this->getContainer();
        $this->setExpectedException('\\SprayFire\\Service\\Exception\\ServiceNotFound');
        $Container->getService('SprayFire.NonExistent.Service');
    }

    public function testCreatingServiceUsingRegisteredFactory() {
        $Container = $this->getContainer();
        $Factory = new FactoryKeyTester();
        $Container->registerFactory(FactoryKeyTester::CONTAINER_KEY, $Factory);
        $Container->addService('Testing.Factory.Creation', null, FactoryKeyTester::CONTAINER_KEY);

        $service = $Container->getService('Testing.Factory.Creation');

        $this->assertInstanceOf('\stdClass', $service);
        $this->assertSame($service->property, 'factory created');
    }

    public function testCreatingServiceUsingUnregisteredFactory() {
        $Container = $this->getContainer();
        $Container->addService('SprayFire.StdLib.ReflectionCache', null, FactoryKeyTester::CONTAINER_KEY);

        $this->setExpectedException('\SprayFire\Service\Exception\FactoryNotRegistered');
        $Container->getService('SprayFire.StdLib.ReflectionCache');
    }

    public function testThrowingExceptionIfServiceAddedIsNotValidClass() {
        $Container = $this->getContainer();
        $Container->addService('This.Is.Not.Valid');

        $this->setExpectedException('\SprayFire\Service\Exception\ServiceNotFound');
        $Container->getService('This.Is.Not.Valid');
    }

    protected function getContainer() {
        $this->ReflectionCache = new \SprayFire\StdLib\ReflectionCache();
        return new \SprayFire\Service\FireService\Container($this->ReflectionCache);
    }

}

class Value {}

class FactoryKeyTester implements SFFactory\Factory {

    const CONTAINER_KEY = 'factory_key_tester';

    public function __toString() {
        return '';
    }

    public function equals(\SprayFire\Object $Object) {

    }

    public function getNullObjectType() {

    }

    public function getObjectType() {

    }

    public function hashCode() {

    }

    public function makeObject($objectName, array $options = array()) {
        $object = new \stdClass();
        $object->property = 'factory created';
        return $object;
    }
}
