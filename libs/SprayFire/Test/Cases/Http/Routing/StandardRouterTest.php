<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of StandardRouterTest
 */

namespace SprayFire\Test\Cases\Http\Routing;

class StandardRouterTest extends \PHPUnit_Framework_TestCase {

    public function testStandardRouterWithGivenControllerActionAndParams() {
        $_server = array();
        $_server['REQUEST_URI'] = '/SprayFire/controller/action/param1/param2/param3';
        $Uri = new \SprayFire\Http\ResourceIdentifier($_server);
        $Headers = new \SprayFire\Http\StandardRequestHeaders($_server);
        $Request = new \SprayFire\Http\StandardRequest($Uri, $Headers);
        $Router = new \SprayFire\Http\Routing\StandardRouter();

        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
    }

}
