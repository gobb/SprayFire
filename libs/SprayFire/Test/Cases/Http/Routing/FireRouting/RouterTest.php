<?php

/**
 * Test cases for SprayFire.Http.Routing.FireRouting.Router to ensure that the
 * appropriate SprayFire.Http.Routing.RoutedRequest
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Http\Routing\FireRouting;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Http\Routing\FireRouting as FireRouting;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Cases.Http.Routing.FireRouting
 */
class RouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * Assures that the Router will properly use default values in configuration
     * if there are none present in the request.
     */
    public function testStandardRouterGoingToRootPath() {
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())
                  ->method('getPattern')
                  ->will($this->returnValue('/'));
        $MockRoute->expects($this->once())
                  ->method('getControllerNamespace')
                  ->will($this->returnValue('SprayFire.Test.Helpers.Controller'));
        $MockRoute->expects($this->once())
                  ->method('getControllerClass')
                  ->will($this->returnValue('test_pages'));
        $MockRoute->expects($this->once())
                  ->method('getAction')
                  ->will($this->returnValue('index-yo-dog'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer, 'SprayFire');

        $Request = $this->getRequest('/SprayFire/');
        $RoutedRequest = $Router->getRoutedRequest($Request);

        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayFire', $RoutedRequest->getAppNamespace());
        $this->assertSame('SprayFire.Test.Helpers.Controller.TestPages', $RoutedRequest->getController());
        $this->assertSame('indexYoDog', $RoutedRequest->getAction());
        $this->assertSame(array(), $RoutedRequest->getParameters());
    }

    /**
     * Assures that the router will route a request when both a controller name
     * and action are determining the routing.
     */
    public function testStandardRouterGoingToControllerActionWithParam() {
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())->method('getPattern')->will($this->returnValue('/charles/roots_for/(?P<item>[A-Za-z]+)/'));
        $MockRoute->expects($this->once())->method('getControllerNamespace')->will($this->returnValue('FourteenChamps.Controller'));
        $MockRoute->expects($this->once())->method('getControllerClass')->will($this->returnValue('NickSaban'));
        $MockRoute->expects($this->once())->method('getAction')->will($this->returnValue('win'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer, 'college.football');

        $RoutedRequest = $Router->getRoutedRequest($this->getRequest('/college.football/charles/roots_for/alabama'));

        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('FourteenChamps', $RoutedRequest->getAppNamespace());
        $this->assertSame('FourteenChamps.Controller.NickSaban', $RoutedRequest->getController());
        $this->assertSame('win', $RoutedRequest->getAction());
        $this->assertSame(array('item' => 'alabama'), $RoutedRequest->getParameters());
    }

    /**
     * Ensuring that a request will use values provided but the appropriate namespace
     * is used if routed.
     */
    public function testStandardRouterWithTwoParams() {
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())
                  ->method('getPattern')
                  ->will($this->returnValue('/charles/drinks/(?P<brewer>[A-Za-z_]+)/(?P<beer>[A-Za-z_]+)/'));
        $MockRoute->expects($this->once())
                  ->method('getControllerNamespace')
                  ->will($this->returnValue('FavoriteBrew.Controller'));
        $MockRoute->expects($this->once())
                  ->method('getControllerClass')
                  ->will($this->returnValue('Charles'));
        $MockRoute->expects($this->once())
                  ->method('getAction')
                  ->will($this->returnValue('drinks'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer, 'brewmaster');

        $RoutedRequest = $Router->getRoutedRequest($this->getRequest('/brewmaster/charles/drinks/sam_adams/boston_lager'));

        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('FavoriteBrew', $RoutedRequest->getAppNamespace());
        $this->assertSame('FavoriteBrew.Controller.Charles', $RoutedRequest->getController());
        $this->assertSame('drinks', $RoutedRequest->getAction());
        $this->assertSame(array('brewer' => 'sam_adams', 'beer' => 'boston_lager'), $RoutedRequest->getParameters());
    }

    /**
     * Ensures that if a SprayFire.Http.Request is passed to Router::getRoutedRequest
     * twice the same object is returned both times.
     */
    public function testGettingSameRoutedRequestFromSameRequest() {
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())
                  ->method('getPattern')
                  ->will($this->returnValue('/'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRoute);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer, '');
        $Request = $this->getRequest('/');
        $RoutedRequestOne = $Router->getRoutedRequest($Request);
        $RoutedRequestTwo = $Router->getRoutedRequest($Request);
        $this->assertSame($RoutedRequestOne, $RoutedRequestTwo);
    }

    /**
     * Ensures that if a URI path isn't properly matched we still get an expected
     * SprayFire.Http.Routing.RoutedRequest
     */
    public function testGettingRouteWithNoMatchingPattern() {
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())
                  ->method('getControllerNamespace')
                  ->will($this->returnValue('SprayFire.Controller'));
        $MockRoute->expects($this->once())
                  ->method('getControllerClass')
                  ->will($this->returnValue('NoRoute'));
        $MockRoute->expects($this->once())
                  ->method('getAction')
                  ->will($this->returnValue('view'));
        $RouteBag = new FireRouting\RouteBag($MockRoute);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer, '');
        $Request = $this->getRequest('');
        $RoutedRequest = $Router->getRoutedRequest($Request);

        $expectedController = 'SprayFire.Controller.NoRoute';
        $expectedAction = 'view';
        $expectedParameters = array();
        $this->assertSame($expectedController, $RoutedRequest->getController());
        $this->assertSame($expectedAction, $RoutedRequest->getAction());
        $this->assertSame($expectedParameters, $RoutedRequest->getParameters());
    }

    /**
     * Ensures that an appropriate RoutedRequest is returned when multiple matching
     * routes are found, the first match should always be the first Route added
     * to the bag.
     *
     * This test ensures coverage of bug #98.
     * https://github.com/cspray/SprayFire/issues/98
     */
    public function testMultipleRoutesReturnsAppropriateMatch() {
        $MockRouteOne = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRouteOne->expects($this->once())
                     ->method('getPattern')
                     ->will($this->returnValue('/'));
        $MockRouteOne->expects($this->once())
                      ->method('getControllerNamespace')
                      ->will($this->returnValue('Mock.Route.One'));
        $MockRouteOne->expects($this->once())
                     ->method('getControllerClass')
                     ->will($this->returnValue('Test'));
        $MockRouteTwo = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRouteTwo->expects($this->once())
                     ->method('getPattern')
                     ->will($this->returnValue('/something'));
        $RouteBag = new FireRouting\RouteBag();
        $RouteBag->addRoute($MockRouteOne);
        $RouteBag->addRoute($MockRouteTwo);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer, '');

        $RoutedRequest = $Router->getRoutedRequest($this->getRequest('/'));
        $this->assertSame('Mock.Route.One.Test', $RoutedRequest->getController());
    }

    public function testRouterCheckingAppropriateMethod() {
        $NoMatchRoute = $this->getMock('\SprayFire\Http\Routing\Route');
        $NoMatchRoute->expects($this->once())
                     ->method('getControllerNamespace')
                     ->will($this->returnValue('Mock.NoMatch.Route'));
        $NoMatchRoute->expects($this->once())
                     ->method('getControllerClass')
                     ->will($this->returnValue('Test'));

        $PatternMatchWrongMethod = $this->getMock('\SprayFire\Http\Routing\Route');
        $PatternMatchWrongMethod->expects($this->once())
                                ->method('getPattern')
                                ->will($this->returnValue('/method_check/'));
        $PatternMatchWrongMethod->expects($this->once())
                                ->method('getMethod')
                                ->will($this->returnValue('GET'));

        $RouteBag = new FireRouting\RouteBag($NoMatchRoute);
        $RouteBag->addRoute($PatternMatchWrongMethod);
        $Normalizer = new FireRouting\Normalizer();
        $Router = new FireRouting\Router($RouteBag, $Normalizer);

        $Request = $this->getRequest('/method_check', 'POST');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertSame('Mock.NoMatch.Route.Test', $RoutedRequest->getController());
    }

    /**
     * @param string $requestUri
     * @param string $method
     * @return \SprayFire\Http\Request
     */
    protected function getRequest($requestUri, $method = 'GET') {
        $MockUri = $this->getMock('\SprayFire\Http\Uri');
        $MockUri->expects($this->once())->method('getPath')->will($this->returnValue($requestUri));

        $MockRequest = $this->getMock('\SprayFire\Http\Request');
        $MockRequest->expects($this->once())->method('getUri')->will($this->returnValue($MockUri));
        $MockRequest->expects($this->once())->method('getMethod')->will($this->returnValue($method));
        return $MockRequest;
    }

}
