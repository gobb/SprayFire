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

    public function testOnlyParametersAllMarked() {
        $uri = '/sprayfire/:something/:you/:should/:expect_in_default_controller';
        $root = 'sprayfire';
        $RequestUri = new \SprayFire\Routing\Routifier\RequestUri($uri, $root);
        $controller = $RequestUri->getControllerFragment();
        $action = $RequestUri->getActionFragment();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getRequestedUri();

        $this->assertSame(\SprayFire\Routing\Uri::NO_CONTROLLER_REQUESTED, $controller);
        $this->assertSame(\SprayFire\Routing\Uri::NO_ACTION_REQUESTED, $action);
        $this->assertSame(array('something', 'you', 'should', 'expect_in_default_controller'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testControllerAndParametersWithOneMarked() {
        $uri = '/something-else/blog/:jan/27/2012';
        $root = 'something-else';
        $RequestUri = new \SprayFire\Routing\Routifier\RequestUri($uri, $root);
        $controller = $RequestUri->getControllerFragment();
        $action = $RequestUri->getActionFragment();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getRequestedUri();

        $this->assertSame('blog', $controller);
        $this->assertSame(\SprayFire\Routing\Uri::NO_ACTION_REQUESTED, $action);
        $this->assertSame(array('jan', '27', '2012'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testControllerActionAndNamedParameters() {
        $uri = '/news/article/category:something/title:something-else-like-this';
        $root = 'install-dir';
        $RequestUri = new \SprayFire\Routing\Routifier\RequestUri($uri, $root);
        $controller = $RequestUri->getControllerFragment();
        $action = $RequestUri->getActionFragment();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getRequestedUri();

        $this->assertSame('news', $controller);
        $this->assertSame('article', $action);
        $this->assertSame(array('category' => 'something', 'title' => 'something-else-like-this'), $parameters);
        $this->assertSame($uri, $requestedUri);

    }

    public function tearDown() {

    }

}
