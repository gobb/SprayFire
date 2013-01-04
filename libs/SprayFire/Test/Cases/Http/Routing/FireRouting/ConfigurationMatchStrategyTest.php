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
 * @package SprayFireTest
 * @subpackage Cases.Http.Routing.FireRouting
 */
class ConfigurationMatchStrategyTest extends PHPUnitTestCase {

    /**
     * Ensures that if there are no routes added to a bag that no loop or conditionals
     * are checked and an appropriate default $Route is returned.
     */
    public function testGettingAppropriateRouteAndParametersForEmptyBag() {
        $NoMatchRoute = $this->getMock('\SprayFire\Http\Routing\Route');

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(0));
        $Bag->expects($this->once())
            ->method('getRoute')
            ->with(null)
            ->will($this->returnValue($NoMatchRoute));

        $Request = $this->getMock('\SprayFire\Http\Request');

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $expected = array(
            'Route' => $NoMatchRoute,
            'parameters' => array()
        );
        $this->assertSame($expected, $Strategy->getRouteAndParameters($Bag, $Request));
    }

    public function testMatchingRequestPathToRouteInBag() {
        $NotItRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $NotItRoute->expects($this->once())
                   ->method('getPattern')
                   ->will($this->returnValue('not it'));

        $TheOneRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $TheOneRoute->expects($this->once())
                    ->method('getPattern')
                    ->will($this->returnValue('/the_one/'));

        $NopeRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $NopeRoute->expects($this->never())
                  ->method('getPattern');

        $routes = array(
            $NotItRoute,
            $TheOneRoute,
            $NopeRoute
        );
        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(3));
        $Bag->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator($routes)));

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/the_one/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $expected = array(
            'Route' => $TheOneRoute,
            'parameters' => array()
        );
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $this->assertSame($expected, $actual);
    }

}
