<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of Container
 */

namespace SprayFire\Test\Cases\Service;

class ContainerTest extends \PHPUnit_Framework_TestCase {

    public function testResourceDoesNotExist() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $directoryExist = $Container->doesServiceExist('SprayFire.FileSys.FireFileSys.Paths');
        $this->assertFalse($directoryExist);
    }

    public function testResourceDoesExist() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $Container->addService('SprayFire.FileSys.FireFileSys.Paths');
        $directoryExist = $Container->doesServiceExist('SprayFire.FileSys.FireFileSys.Paths');
        $this->assertTrue($directoryExist);
    }

    public function testAddingNotCallableParameters() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $this->setExpectedException('\\InvalidArgumentException');
        $Container->addService('SprayFire.FileSys.FireFileSys.Paths', array());
    }

    public function testAddingNullParametersDefaultToEmptyCallback() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $Container->addService('SprayFire.Test.Cases.Service.Value', null);
        $Service = $Container->getService('SprayFire.Test.Cases.Service.Value');
        $this->assertInstanceOf('\\SprayFire\\Test\\Cases\\Service\\Value', $Service);
    }

    public function testGettingPathsService() {
        $serviceName = 'SprayFire.FileSys.FireFileSys.Paths';
        $parameters = function() {
            $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
            $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($install);
            return array($RootPaths);
        };
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
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
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $Container->addService($serviceName, $parameters);
        $DirectoryOne = $Container->getService($serviceName);
        $DirectoryTwo = $Container->getService($serviceName);
        $this->assertSame($DirectoryOne, $DirectoryTwo);
    }

    public function testAddingObjectAsServiceAndRetrievingRightObject() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $Container->addService($ReflectionCache, function() {return array();});
        $Cache = $Container->getService('Artax.ReflectionCacher');
        $this->assertSame($ReflectionCache, $Cache);
    }

    public function testAddingObjectAsServiceAndCheckingServiceExists() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $Container->addService($ReflectionCache, function() {return array();});
        $this->assertTrue($Container->doesServiceExist('Artax.ReflectionCacher'));
    }

    public function testGettingServiceThatDoesNotExist() {
        $ReflectionCache = new \Artax\ReflectionCacher();
        $JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
        $Container = new \SprayFire\Service\FireService\Container($ReflectionCache, $JavaNameConverter);
        $this->setExpectedException('\\SprayFire\\Service\\NotFoundException');
        $Container->getService('SprayFire.NonExistent.Service');
    }

}

class Value {}