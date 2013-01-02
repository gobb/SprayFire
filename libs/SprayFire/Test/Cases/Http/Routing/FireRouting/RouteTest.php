<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Test\Cases\Http\Routing\FireRouting;

use \SprayFire\Http\Routing\FireRouting as FireRouting,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFire
 * @subpackage
 */
class RouteTest extends PHPUnitTestCase {

    public function testGetPatternFromRoute() {
        $pattern = 'regular expression pattern';
        $namespace = 'Some.Namespace';
        $Route = new FireRouting\Route($pattern, $namespace);

        $this->assertSame($pattern, $Route->getPattern());
    }

    public function testGettingControllerNamespace() {
        $pattern = 'pattern';
        $namespace = 'Some.Namespace';
        $Route = new FireRouting\Route($pattern, $namespace);
        $this->assertSame($namespace, $Route->getControllerNamespace());
    }

    public function testGettingControllerClass() {
        $pattern = 'pattern';
        $namespace = 'Some.Namespace';
        $controller = 'Controller';
        $Route = new FireRouting\Route($pattern, $namespace, $controller);
        $this->assertSame($controller, $Route->getControllerClass());
    }

    public function testGettingControllerAction() {
        $pattern = 'pattern';
        $namespace = 'Some.Namespace';
        $controller = 'Controller';
        $action = 'action';
        $Route = new FireRouting\Route($pattern, $namespace, $controller, $action);
        $this->assertSame($action, $Route->getAction());
    }

    public function testGettingMethod() {
        $pattern = 'pattern';
        $namespace= 'Some.Namespace';
        $controller = 'Controller';
        $action = 'action';
        $method = 'GET';
        $Route = new FireRouting\Route($pattern, $namespace, $controller, $action, $method);
        $this->assertSame('GET', $Route->getMethod());
    }

}
