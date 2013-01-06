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
        $Route = $data['Route'];
        $parameters = $data['parameters'];

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
        $Route = $data['Route'];
        $parameters = $data['parameters'];

        $this->assertSame($parameters, array());
        $this->assertSame('/', $Route->getPattern());
        $this->assertSame('SprayFire.Controller.FireController', $Route->getControllerNamespace());
        $this->assertSame('Pages', $Route->getControllerClass());
    }


}
