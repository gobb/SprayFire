<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of Container
 */

namespace SprayFire\Test\Cases\Service\FireBox;

class ContainerTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {

    }

    public function testResourceDoesNotExist() {
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $directoryExist = $Container->doesServiceExist('SprayFire.Util.Directory');
        $this->assertFalse($directoryExist);
    }

    public function testResourceDoesExist() {
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService('SprayFire.Util.Directory', function() {});
        $directoryExist = $Container->doesServiceExist('SprayFire.Util.Directory');
        $this->assertTrue($directoryExist);
    }

    public function testAddingNotCallableParameters() {
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $this->setExpectedException('\\InvalidArgumentException');
        $Container->addService('SprayFire.Util.Directory', array());
    }

    public function testGettingDirectoryService() {
        $serviceName = 'SprayFire.Util.Directory';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $arg1 = \compact('install');
            return array($arg1);
        };
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($serviceName, $parameters);
        $Directory = $Container->getService($serviceName);
        $this->assertInstanceOf('\\SprayFire\\Util\\Directory', $Directory);
    }

    public function testCachingServices() {
        $serviceName = 'SprayFire.Util.Directory';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $arg1 = \compact('install');
            return array($arg1);
        };
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($serviceName, $parameters);
        $DirectoryOne = $Container->getService($serviceName);
        $DirectoryTwo = $Container->getService($serviceName);
        $this->assertSame($DirectoryOne, $DirectoryTwo);
    }

    public function testAddingObjectAsServiceAndRetrievingRightObject() {
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($ReflectionCache, function() {return array();});
        $Cache = $Container->getService('Artax.ReflectionPool');
        $this->assertSame($ReflectionCache, $Cache);
    }

    public function testAddingObjectAsServiceAndCheckingServiceExists() {
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($ReflectionCache, function() {return array();});
        $this->assertTrue($Container->doesServiceExist('Artax.ReflectionPool'));
    }

    public function testGettingServiceThatDoesNotExist() {
        $ReflectionCache = new \Artax\ReflectionPool();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $this->setExpectedException('\\SprayFire\\Service\\NotFoundException');
        $Container->getService('SprayFire.NonExistent.Service');
    }

    public function tearDown() {

    }

}
