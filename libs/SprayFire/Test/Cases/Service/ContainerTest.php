<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of Container
 */

namespace SprayFire\Test\Cases\Service;

class ContainerTest extends \PHPUnit_Framework_TestCase {

    public function testResourceDoesNotExist() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $directoryExist = $Container->doesServiceExist('SprayFire.FileSys.Paths');
        $this->assertFalse($directoryExist);
    }

    public function testResourceDoesExist() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService('SprayFire.FileSys.Paths', function() {});
        $directoryExist = $Container->doesServiceExist('SprayFire.FileSys.Paths');
        $this->assertTrue($directoryExist);
    }

    public function testAddingNotCallableParameters() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $this->setExpectedException('\\InvalidArgumentException');
        $Container->addService('SprayFire.FileSys.Paths', array());
    }

    public function testAddingNullParametersDefaultToEmptyCallback() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService('SprayFire.Test.Cases.Service.Value', null);
        $Service = $Container->getService('SprayFire.Test.Cases.Service.Value');
        $this->assertInstanceOf('\\SprayFire\\Test\\Cases\\Service\\Value', $Service);
    }

    public function testGettingPathsService() {
        $serviceName = 'SprayFire.FileSys.Paths';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $RootPaths = new \SprayFire\FileSys\RootPaths($install);
            return array($RootPaths);
        };
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($serviceName, $parameters);
        $Directory = $Container->getService($serviceName);
        $this->assertInstanceOf('\\SprayFire\\FileSys\\Paths', $Directory);
    }

    public function testCachingServices() {
        $serviceName = 'SprayFire.FileSys.Paths';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $RootPaths = new \SprayFire\FileSys\RootPaths($install);
            return array($RootPaths);
        };
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($serviceName, $parameters);
        $DirectoryOne = $Container->getService($serviceName);
        $DirectoryTwo = $Container->getService($serviceName);
        $this->assertSame($DirectoryOne, $DirectoryTwo);
    }

    public function testAddingObjectAsServiceAndRetrievingRightObject() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($ReflectionCache, function() {return array();});
        $Cache = $Container->getService('Artax.ReflectionCacher');
        $this->assertSame($ReflectionCache, $Cache);
    }

    public function testAddingObjectAsServiceAndCheckingServiceExists() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $Container->addService($ReflectionCache, function() {return array();});
        $this->assertTrue($Container->doesServiceExist('Artax.ReflectionCacher'));
    }

    public function testGettingServiceThatDoesNotExist() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $Container = new \SprayFire\Service\FireBox\Container($ReflectionCache);
        $this->setExpectedException('\\SprayFire\\Service\\NotFoundException');
        $Container->getService('SprayFire.NonExistent.Service');
    }

}

class Value {}