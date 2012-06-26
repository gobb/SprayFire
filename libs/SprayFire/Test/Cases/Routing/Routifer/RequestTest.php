<?php

/**
 *
 */

namespace SprayFire\Test\Cases;

class RequestTest extends \PHPUnit_Framework_TestCase {

    public function testSimpleRequestedUri() {
        $uri = '/sprayfire/controller/action/1/2/3';
        $root = 'sprayfire';
        $UriData = new \SprayFire\Routing\Routifier\UriData($uri, $root);
        $RequestUri = new \SprayFire\Routing\Routifier\Request($UriData);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('controller', $controller);
        $this->assertSame('action', $action);
        $this->assertSame(array('1', '2', '3'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testEmptyRequestedUri() {
        $uri = '';
        $root = 'sprayfire';
        $UriData = new \SprayFire\Routing\Routifier\UriData($uri, $root);
        $RequestUri = new \SprayFire\Routing\Routifier\Request($UriData);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('page', $controller);
        $this->assertSame('index', $action);
        $this->assertSame(array(), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testControllerOnlyInUri() {
        $uri = '/roll_tide/';
        $root = '';
        $UriData = new \SprayFire\Routing\Routifier\UriData($uri, $root);
        $RequestUri = new \SprayFire\Routing\Routifier\Request($UriData);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('roll_tide', $controller);
        $this->assertSame('index', $action);
        $this->assertSame(array(), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testOnlyParametersAllMarked() {
        $uri = '/sprayfire/:something/:you/:should/:expect_in_default_controller';
        $root = 'sprayfire';
        $UriData = new \SprayFire\Routing\Routifier\UriData($uri, $root);
        $RequestUri = new \SprayFire\Routing\Routifier\Request($UriData);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('page', $controller);
        $this->assertSame('index', $action);
        $this->assertSame(array('something', 'you', 'should', 'expect_in_default_controller'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testControllerAndParametersWithOneMarked() {
        $uri = '/something-else/blog/:jan/27/2012';
        $root = 'something-else';
        $UriData = new \SprayFire\Routing\Routifier\UriData($uri, $root);
        $RequestUri = new \SprayFire\Routing\Routifier\Request($UriData);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('blog', $controller);
        $this->assertSame('index', $action);
        $this->assertSame(array('jan', '27', '2012'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

    public function testControllerActionAndNamedParameters() {
        $uri = '/news/article/category:something/title:something-else-like-this';
        $root = 'install-dir';
        $UriData = new \SprayFire\Routing\Routifier\UriData($uri, $root);
        $RequestUri = new \SprayFire\Routing\Routifier\Request($UriData);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('news', $controller);
        $this->assertSame('article', $action);
        $this->assertSame(array('category' => 'something', 'title' => 'something-else-like-this'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

}
