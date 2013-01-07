<?php

/**
 * Test to ensure that we can parse a pretty URL into an appropriate Route and
 * parameters based on a common convention.
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
 * @package SprayFireTest
 * @subpackage Cases.Http.Routing.FireRouting
 */
class ConventionMatchStrategyTest extends PHPUnitTestCase {

    /**
     * Ensures that if a root path is determined to be the path for the request
     * default options are used.
     */
    public function testGettingAppropriateRouteWithOnlyDefaultsForRootPath() {
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConventionMatchStrategy();
        $data = $Strategy->getRouteAndParameters($Bag, $Request);
        /** @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[FireRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[FireRouting\MatchStrategy::PARAMETER_KEY];

        $this->assertSame($parameters, array());
        $this->assertSame('/', $Route->getPattern());
        $this->assertSame('SprayFire.Controller.FireController', $Route->getControllerNamespace());
        $this->assertSame('Pages', $Route->getControllerClass());
    }

    /**
     * Ensures that if a root path with an install directory the appropriate
     * default options are used for Route object.
     */
    public function testEnsureRootWithInstallDirectoryRemoved() {
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/sprayfire'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConventionMatchStrategy(array('installDirectory' => 'sprayfire'));
        $data = $Strategy->getRouteAndParameters($Bag, $Request);
        /** @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[FireRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[FireRouting\MatchStrategy::PARAMETER_KEY];

        $this->assertSame($parameters, array());
        $this->assertSame('/', $Route->getPattern());
        $this->assertSame('SprayFire.Controller.FireController', $Route->getControllerNamespace());
        $this->assertSame('Pages', $Route->getControllerClass());
    }

    /**
     * Ensures that if we get a controller fragment in the path we return that
     * fragment as the controller class from the route while still using default
     * options where appropriate and passing the path as the pattern.
     */
    public function testEnsureControllerFragmentParsedCorrectlyPassingActionInOptions() {
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/controller'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConventionMatchStrategy(array('action' => 'index_yo_dog'));
        $data = $Strategy->getRouteAndParameters($Bag, $Request);
        /** @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[FireRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[FireRouting\MatchStrategy::PARAMETER_KEY];

        $this->assertSame($parameters, array());
        $this->assertSame('/controller', $Route->getPattern());
        $this->assertSame('SprayFire.Controller.FireController', $Route->getControllerNamespace());
        $this->assertSame('controller', $Route->getControllerClass());
        $this->assertSame('index_yo_dog', $Route->getAction());
    }

    /**
     * Ensures that if a controller and action are present in the path they are
     * returned as appropriate and the namespace option can be set to a defined
     * value.
     */
    public function testEnsureControllerAndActionFragmentParsedCorrectlyPassingNamespaceInOptions() {
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/controller/action/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConventionMatchStrategy(array('namespace' => 'SomeApp.Controller'));
        $data = $Strategy->getRouteAndParameters($Bag, $Request);
        /** @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[FireRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[FireRouting\MatchStrategy::PARAMETER_KEY];

        $this->assertSame($parameters, array());
        $this->assertSame('/controller/action/', $Route->getPattern());
        $this->assertSame('SomeApp.Controller', $Route->getControllerNamespace());
        $this->assertSame('controller', $Route->getControllerClass());
        $this->assertSame('action', $Route->getAction());
    }

    /**
     * Ensures that if parameters are present after the action they are properly returned.
     */
    public function testEnsureGettingMultipleParametersIfPresent() {
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/controller/action/1/2/3/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConventionMatchStrategy();
        $data = $Strategy->getRouteAndParameters($Bag, $Request);
        /** @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[FireRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[FireRouting\MatchStrategy::PARAMETER_KEY];

        $this->assertSame($parameters, array('1', '2', '3'));
        $this->assertSame('/controller/action/1/2/3/', $Route->getPattern());
        $this->assertSame('SprayFire.Controller.FireController', $Route->getControllerNamespace());
        $this->assertSame('controller', $Route->getControllerClass());
        $this->assertSame('action', $Route->getAction());
    }

    /**
     * Ensures that named parameters are properly parsed and returned as associative
     * keys.
     */
    public function testEnsureGettingMultipleNamedParameters() {
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/controller/action/category:php/title:some-slug/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConventionMatchStrategy();
        $data = $Strategy->getRouteAndParameters($Bag, $Request);
        /** @var \SprayFire\Http\Routing\Route $Route */
        $Route = $data[FireRouting\MatchStrategy::ROUTE_KEY];
        $parameters = $data[FireRouting\MatchStrategy::PARAMETER_KEY];

        $this->assertSame($parameters, array('category' => 'php', 'title' => 'some-slug'));
        $this->assertSame('/controller/action/category:php/title:some-slug/', $Route->getPattern());
        $this->assertSame('SprayFire.Controller.FireController', $Route->getControllerNamespace());
        $this->assertSame('controller', $Route->getControllerClass());
        $this->assertSame('action', $Route->getAction());
    }

    

}
