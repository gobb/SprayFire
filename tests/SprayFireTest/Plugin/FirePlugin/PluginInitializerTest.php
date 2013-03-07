<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of AppInitializerTest
 */

namespace SprayFireTest\Plugin\FirePlugin;

use \SprayFire\Plugin\FirePlugin as FirePlugin,
    \SprayFire\Dispatcher\FireDispatcher as SFDispatcher,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Plugin.FirePlugin
 */
class PluginInitializerTest extends PHPUnitTestCase {

    protected $Container;

    protected $ClassLoader;

    public function setUp() {

    }

    public function testAppInitializerRunningAppBootstrap() {
        $appName = 'TestApp';
        $Initializer = $this->getInitializer($appName);
        $Initializer->initializePlugin($appName);
        $this->assertTrue($this->Container->doesServiceExist('TestApp.Service.FromBootstrap'), 'The service from the app bootstrap was not loaded');
    }

    public function testAppInitializerWithInvalidNamespace() {
        $appName = 'NonExistent';
        $Initializer = $this->getInitializer($appName);
        $this->setExpectedException('\\SprayFire\\Plugin\\Exception\\PluginBootstrapNotFound');
        $Initializer->initializePlugin($appName);
    }

    public function testAppInitializerWithAppBootstrapNotBootstrapper() {
        $appName = 'AnotherApp';
        $Initializer = $this->getInitializer($appName);
        $this->setExpectedException('\\SprayFire\\Plugin\\Exception\\PluginBootstrapWrongType');
        $Initializer->initializePlugin($appName);
    }

    protected function getInitializer($appName) {
        $this->Container = $this->getServiceContainer();
        $this->ClassLoader = $this->getClassLoader();
        $this->ClassLoader->setAutoloader();
        $this->ClassLoader->registerNamespaceDirectory($appName, \SPRAYFIRE_ROOT . '/tests/SprayFireTest/mockframework/app');
        return new FirePlugin\PluginInitializer($this->Container, $this->ClassLoader);
    }

    protected function getServiceContainer() {
        $ReflectionCache = new \SprayFire\StdLib\ReflectionCache();
        return new \SprayFire\Service\FireService\Container($ReflectionCache);
    }

    protected function getClassLoader() {
        return new \ClassLoader\Loader();
    }

}
