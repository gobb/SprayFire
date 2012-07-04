<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of StandardRouterTest
 */

namespace SprayFire\Test\Cases\Http\Routing;

class StandardRouterTest extends \PHPUnit_Framework_TestCase {

    public function testStandardRouterWithGivenControllerActionAndParams() {
        $_server = array();
        $_server['REQUEST_URI'] = '/SprayFire/controller/action/param1/param2/param3/';
        $Uri = new \SprayFire\Http\ResourceIdentifier($_server);
        $Headers = new \SprayFire\Http\StandardRequestHeaders($_server);
        $Request = new \SprayFire\Http\StandardRequest($Uri, $Headers);

        $Normalizer = new \SprayFire\Http\Routing\Normalizer();
        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/config/SprayFire/routes.json';
        $Router = new \SprayFire\Http\Routing\StandardRouter($Normalizer, $configPath, 'SprayFire');

        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayTest.Controller.Controller', $RoutedRequest->getController());
        $this->assertSame('action', $RoutedRequest->getAction());
        $this->assertSame(array('param1', 'param2', 'param3'), $RoutedRequest->getParameters());
    }

    public function testStandardRouterWithNoControllerActionOrParams() {
        $_server = array();
        $_server['REQUEST_URI'] = '/SprayFire/';
        $Uri = new \SprayFire\Http\ResourceIdentifier($_server);
        $Headers = new \SprayFire\Http\StandardRequestHeaders($_server);
        $Request = new \SprayFire\Http\StandardRequest($Uri, $Headers);

        $Normalizer = new \SprayFire\Http\Routing\Normalizer();
        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/config/SprayFire/routes.json';
        $Router = new \SprayFire\Http\Routing\StandardRouter($Normalizer, $configPath, 'SprayFire');

        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayTest.Controller.TestPages', $RoutedRequest->getController());
        $this->assertSame('indexYoDog', $RoutedRequest->getAction());
        $this->assertSame(array('yo', 'dog'), $RoutedRequest->getParameters());
    }

    public function testStandardRouterWithRoutedbyControllerOnly() {
        $_server = array();
        $_server['REQUEST_URI'] = '/love.game/charles/loves/dyana';
        $Uri = new \SprayFire\Http\ResourceIdentifier($_server);
        $Headers = new \SprayFire\Http\StandardRequestHeaders($_server);
        $Request = new \SprayFire\Http\StandardRequest($Uri, $Headers);

        $Normalizer = new \SprayFire\Http\Routing\Normalizer();
        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/config/SprayFire/routes.json';
        $Router = new \SprayFire\Http\Routing\StandardRouter($Normalizer, $configPath, 'love.game');

        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayTest.Controller.TestPages', $RoutedRequest->getController());
        $this->assertSame('indexYoDog', $RoutedRequest->getAction());
        $this->assertSame(array('yo', 'dog'), $RoutedRequest->getParameters());
    }

}
