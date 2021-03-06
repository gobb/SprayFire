<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFireTest\Http\Routing\FireRouting;

use \SprayFire\Http\Routing\FireRouting as FireRouting,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Http.Routing.FireRouting
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
            $Strategy::ROUTE_KEY => $NoMatchRoute,
            $Strategy::PARAMETER_KEY => array()
        );
        $this->assertSame($expected, $Strategy->getRouteAndParameters($Bag, $Request));
    }

    /**
     * Ensure that if a Route pattern in a bag matches the Request path that the
     * appropriate Route is returned.
     */
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
            $Strategy::ROUTE_KEY => $TheOneRoute,
            $Strategy::PARAMETER_KEY => array()
        );
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $this->assertSame($expected, $actual);
    }

    /**
     * Ensures that if no matched route is in a bag the default route for that
     * bag will be returned.
     */
    public function testEnsureNoMatchRouteIfNoMatchInBag() {
        $DefaultRoute = $this->getMock('\SprayFire\Http\Routing\Route');

        $BlankRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $BlankRoute->expects($this->once())
                   ->method('getPattern')
                   ->will($this->returnValue(''));

        $SprayFireRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $SprayFireRoute->expects($this->once())
                       ->method('getPattern')
                       ->will($this->returnValue('/sprayfire/'));

        $FooRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $FooRoute->expects($this->once())
                 ->method('getPattern')
                 ->will($this->returnValue('/foo/'));

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(3));
        $Bag->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array($BlankRoute, $SprayFireRoute, $FooRoute))));
        $Bag->expects($this->once())
            ->method('getRoute')
            ->with(null)
            ->will($this->returnValue($DefaultRoute));

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/bar/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $expected = array(
            $Strategy::ROUTE_KEY => $DefaultRoute,
            $Strategy::PARAMETER_KEY => array()
        );
        $this->assertSame($expected, $actual);
    }

    public function testStrategyHandlesEmptyPath() {
        $Route = $this->getMock('\SprayFire\Http\Routing\Route');
        $Route->expects($this->once())->method('getPattern')->will($this->returnValue('/'));

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())->method('count')->will($this->returnValue(1));

        $Bag->expects($this->once())->method('getIterator')->will($this->returnValue(new \ArrayIterator([$Route])));

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())->method('getPath')->will($this->returnValue('/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())->method('getUri')->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $expected = [$Strategy::ROUTE_KEY => $Route, $Strategy::PARAMETER_KEY => []];
        $this->assertSame($expected, $actual, 'Failed asserting that root URI path is handled appropriately');
    }

    public function testRouteNotBeingMatchedBecauseOfMethod() {
        $DefaultRoute = $this->getMock('\SprayFire\Http\Routing\Route');

        $BlankRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $BlankRoute->expects($this->once())
                   ->method('getPattern')
                   ->will($this->returnValue(''));

        $FooRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $FooRoute->expects($this->once())
                 ->method('getPattern')
                 ->will($this->returnValue('/foo/'));

        $MatchRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MatchRoute->expects($this->once())
                   ->method('getPattern')
                   ->will($this->returnValue('/match/'));
        $MatchRoute->expects($this->once())
                   ->method('getMethod')
                   ->will($this->returnValue('GET'));

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(3));

        $Bag->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array($BlankRoute, $FooRoute, $MatchRoute))));

        $Bag->expects($this->once())
            ->method('getRoute')
            ->with(null)
            ->will($this->returnValue($DefaultRoute));

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/match/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $expected = array(
            $Strategy::ROUTE_KEY => $DefaultRoute,
            $Strategy::PARAMETER_KEY => array()
        );
        $this->assertSame($expected, $actual);
    }

    public function testEnsureInstallDirectoryIsAppropriatelyRemovedInComparison() {
        $MatchedRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MatchedRoute->expects($this->once())
                     ->method('getPattern')
                     ->will($this->returnValue('/match/'));

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(1));

        $Bag->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array($MatchedRoute))));
        $Bag->expects($this->never())
            ->method('getRoute');

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/install/match/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy('install');
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $expected = array(
            $Strategy::ROUTE_KEY => $MatchedRoute,
            $Strategy::PARAMETER_KEY => array()
        );
        $this->assertSame($expected, $actual);
    }

    public function testParametersAreMatchedWithSubGroupRoutePattern() {
        $MatchedRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MatchedRoute->expects($this->once())
                     ->method('getPattern')
                     ->will($this->returnValue('/charles/drinks/(?P<brewer>[A-Za-z_]+)/(?P<beer>[A-Za-z_]+)/'));

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(1));

        $Bag->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array($MatchedRoute))));

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('/charles/drinks/sam_adams/boston_lager/'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $expected = array(
            $Strategy::ROUTE_KEY => $MatchedRoute,
            $Strategy::PARAMETER_KEY => array(
                'brewer' => 'sam_adams',
                'beer' => 'boston_lager'
            )
        );
        $this->assertSame($expected, $actual);
    }

    public function testMatchingPathWithNoLeadingOrTrailingSlash() {
        $MatchedRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $MatchedRoute->expects($this->once())
            ->method('getPattern')
            ->will($this->returnValue('/match/'));

        $Bag = $this->getMock('\SprayFire\Http\Routing\RouteBag');
        $Bag->expects($this->once())
            ->method('count')
            ->will($this->returnValue(1));

        $Bag->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array($MatchedRoute))));
        $Bag->expects($this->never())
            ->method('getRoute');

        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Uri->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue('match'));
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($Uri));

        $Strategy = new FireRouting\ConfigurationMatchStrategy();
        $actual = $Strategy->getRouteAndParameters($Bag, $Request);
        $expected = array(
            $Strategy::ROUTE_KEY => $MatchedRoute,
            $Strategy::PARAMETER_KEY => array()
        );
        $this->assertSame($expected, $actual);
    }

}
