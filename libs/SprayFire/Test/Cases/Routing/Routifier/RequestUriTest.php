<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of RequestUri
 */

namespace SprayFire\Test\Cases;

class RequestUriTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {

    }

    public function testSimpleRequestedUri() {
        $uri = '/sprayfire/controller/action/1/2/3';
        $root = 'sprayfire';
        $RequestUri = new \SprayFire\Routing\Routifier\RequestUri($uri, $root);
        $controller = $RequestUri->getControllerFragment();
        $action = $RequestUri->getActionFragment();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getRequestedUri();

        $this->assertSame('controller', $controller);
        $this->assertSame('action', $action);
        $this->assertSame(array('1', '2', '3'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testEmptyRequestedUri() {
        $uri = '';
        $root = 'sprayfire';
        $RequestUri = new \SprayFire\Routing\Routifier\RequestUri($uri, $root);
        $controller = $RequestUri->getControllerFragment();
        $action = $RequestUri->getActionFragment();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getRequestedUri();

        $this->assertSame(\SprayFire\Routing\Uri::NO_CONTROLLER_REQUESTED, $controller);
        $this->assertSame(\SprayFire\Routing\Uri::NO_ACTION_REQUESTED, $action);
        $this->assertSame(array(), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testControllerOnlyInUri() {
        $uri = '/roll_tide/';
        $root = '';
        $RequestUri = new \SprayFire\Routing\Routifier\RequestUri($uri, $root);
        $controller = $RequestUri->getControllerFragment();
        $action = $RequestUri->getActionFragment();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getRequestedUri();

        $this->assertSame('roll_tide', $controller);
        $this->assertSame(\SprayFire\Routing\Uri::NO_ACTION_REQUESTED, $action);
        $this->assertSame(array(), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function tearDown() {

    }

}
