<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of AppInitializerTest
 */

namespace SprayFire\Test\Cases;

class AppInitializerTest extends \PHPUnit_Framework_TestCase {

    protected $Container;

    protected $ClassLoader;

    public function setUp() {

    }

    public function testAppInitializerRunningAppBootstrap() {
        $Initializer = $this->getInitializer();
        $controller = 'TestApp.Controller.Something';
        $RoutedRequest = $this->getRoutedRequest($controller);
        $Initializer->initializeApp($RoutedRequest);
        $this->assertTrue($this->Container->doesServiceExist('TestApp.Service.FromBootstrap'), 'The service from the app bootstrap was not loaded');
    }

    public function testAppInitializerWithInvalidNamespace() {
        $Initializer = $this->getInitializer();
        $controller = 'NonExistent.Controller';
        $RoutedRequest = $this->getRoutedRequest($controller);
        $this->setExpectedException('\\SprayFire\\Dispatcher\\Exception\\BootstrapNotFound');
        $Initializer->initializeApp($RoutedRequest);
    }

    public function testAppInitializerWithAppBootstrapNotBootstrapper() {
        $Initializer = $this->getInitializer();
        $controller = 'AnotherApp.Controller.Page';
        $RoutedRequest = $this->getRoutedRequest($controller);
        $this->setExpectedException('\\SprayFire\\Dispatcher\\Exception\\NotBootstrapperInstance');
        $Initializer->initializeApp($RoutedRequest);
    }

    protected function getInitializer() {
        $this->Container = $this->getServiceContainer();
        $this->ClassLoader = $this->getClassLoader();
        $this->ClassLoader->setAutoloader();
        $installDir = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework';
        $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($installDir);
        $Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);
        return new \SprayFire\Dispatcher\FireDispatcher\AppInitializer($this->Container, $Paths, $this->ClassLoader);
    }

    protected function getServiceContainer() {
        $JavaNameConverter = new \SprayFire\Utils\JavaNamespaceConverter();
        $ReflectionCache = new \SprayFire\Utils\ReflectionCache($JavaNameConverter);
        return new \SprayFire\Service\FireService\Container($ReflectionCache);
    }

    protected function getRoutedRequest($controller, $action = 'index', array $parameters = array(), $isStatic = false) {
        return new \SprayFire\Http\Routing\FireRouting\RoutedRequest($controller, $action, $parameters, $isStatic);
    }

    protected function getClassLoader() {
        return new \ClassLoader\Loader();
    }

    public function tearDown() {

    }

}
