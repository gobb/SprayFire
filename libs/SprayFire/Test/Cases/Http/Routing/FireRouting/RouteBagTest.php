<?php

/**
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Http\Routing\FireRouting;

use \SprayFire\Http\Routing\FireRouting as FireRouting;

/**
 * @package SprayFireTests
 * @subpackage Cases.Http.Routing.FireRouting
 */
class RouteBagTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that a SprayFire.Http.Routing.Route added to the RouteBag returns
     * true that it has a Route against the appropriate pattern.
     *
     * @covers \SprayFire\Http\Routing\FireRouting\RouteBag::addRoute
     * @covers \SprayFire\Http\Routing\FireRouting\RouteBag::hasRouteWithPattern
     */
    public function testRouteBagAddingRouteAndHavingThatRoute() {
        $MockRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRoute->expects($this->once())
                  ->method('getPattern')
                  ->will($this->returnValue('/'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);

        $this->assertTrue($RouteBag->hasRouteWithPattern('/'), 'Does not have a proper Route stored against the given pattern');
    }

    /**
     * Ensures that a Route added can properly be removed based on the pattern
     * associated to the Route.
     *
     * @covers \SprayFire\Http\Routing\FireRouting\RouteBag::removeRouteWithPattern
     */
    public function testRouteBagAddingRouteAndRemovingIt() {
        $MockRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRoute->expects($this->once())->method('getPattern')->will($this->returnValue('/'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);
        $this->assertTrue($RouteBag->hasRouteWithPattern('/'), 'Does not have a proper Route stored against the given pattern');

        $RouteBag->removeRouteWithPattern('/');

        $this->assertFalse($RouteBag->hasRouteWithPattern('/'), 'Did not properly remove the Route with the given pattern');
    }

    /**
     * Ensures that we can count the number of routes in a RouteBag by using the
     * PHP count() method.
     *
     * @covers \SprayFire\Http\Routing\FireRouting\RouteBag::count
     */
    public function testCountingRoutesInRouteBag() {
        $MockRouteOne = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteOne->expects($this->once())->method('getPattern')->will($this->returnValue('one'));
        $MockRouteTwo = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteTwo->expects($this->once())->method('getPattern')->will($this->returnValue('two'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRouteOne);
        $RouteBag->addRoute($MockRouteTwo);

        $this->assertCount(2, $RouteBag, 'The RouteBag does not have the appropriate number of Routes stored in it.');
    }

    /**
     * Ensures that the RouteBag is iterated over in an order that we expect,
     * first in first out order.
     *
     * @covers \SprayFire\Http\Routing\FireRouting\RouteBag::getIterator
     */
    public function testIteratingOverThreeRoutesInRouteBag() {
        $MockRouteOne = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteOne->expects($this->once())->method('getPattern')->will($this->returnValue('one'));
        $MockRouteTwo = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteTwo->expects($this->once())->method('getPattern')->will($this->returnValue('two'));
        $MockRouteThree = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteThree->expects($this->once())->method('getPattern')->will($this->returnValue('three'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRouteOne);
        $RouteBag->addRoute($MockRouteTwo);
        $RouteBag->addRoute($MockRouteThree);

        $expectedRoutePatterns = array(
            'one',
            'two',
            'three'
        );

        $expectedRoutes = array(
            $MockRouteOne,
            $MockRouteTwo,
            $MockRouteThree
        );

        $i = 0;
        foreach($RouteBag as $routePattern => $Route) {
            $this->assertSame($expectedRoutePatterns[$i], $routePattern);
            $this->assertSame($expectedRoutes[$i], $Route);
            $i++;
        }

        $this->assertSame(3, $i, 'The foreach loop did not iterate the appropriate number of times');
    }

    /**
     * Ensures that a route pattern can only be added to the collection one time,
     * preventing route objects from being overwritten.
     *
     * @covers \SprayFire\Http\Routing\FireRouting\RouteBag::addRoute
     */
    public function testRouteBagThrowingExceptionIfSamePatternPassedMultipleTimes() {
        $MockRouteOne = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteOne->expects($this->once())->method('getPattern')->will($this->returnValue('one'));
        $MockRouteTwo = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRouteTwo->expects($this->once())->method('getPattern')->will($this->returnValue('one'));

        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRouteOne);

        $this->setExpectedException('\SprayFire\Http\Routing\Exception\DuplicateRouteAdded');
        $RouteBag->addRoute($MockRouteTwo);
    }

    /**
     * Ensures that we get an appropriate SprayFire.Http.Routing.Route object
     * matching a supplied pattern.
     */
    public function testRouteBagGettingRouteMatchingPattern() {
        $MockRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MockRoute->expects($this->once())
                  ->method('getPattern')
                  ->will($this->returnValue('test'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);

        $this->assertSame($MockRoute, $RouteBag->getRoute('test'));
    }

    /**
     * Ensures that if no parameter is passed to RouteBag::getRoute() an object
     * is properly returned.
     */
    public function testRouteBagGettingRouteWithNoPattern() {
        $MockRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $RouteBag = new FireRouting\RouteBag($MockRoute);
        $this->assertSame($MockRoute, $RouteBag->getRoute());
    }

}
