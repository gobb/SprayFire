<?php

/**
 *
 */

namespace SprayFire\Test\Cases;

class RequestTest extends \PHPUnit_Framework_TestCase {

    public function testSimpleRequestedUri() {
        $uri = '/sprayfire/controller/action/1/2/3';
        $root = 'sprayfire';
        $RequestUri = new \SprayFire\Routing\Routifier\Request($uri, $root);
        $controller = $RequestUri->getController();
        $action = $RequestUri->getAction();
        $parameters = $RequestUri->getParameters();
        $requestedUri = $RequestUri->getUri();

        $this->assertSame('controller', $controller);
        $this->assertSame('action', $action);
        $this->assertSame(array('1', '2', '3'), $parameters);
        $this->assertSame($uri, $requestedUri);
    }

}
