<?php

/**
 * Test cases for \SprayFire\Routing\FireRouting\Router to ensure that the
 * appropriate \SprayFire\Routing\RoutedRequest is returned.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Routing\FireRouting;

use \SprayFire\Routing as SFRouting,
    \SprayFire\Routing\FireRouting as FireRouting,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Cases.Http.Routing.FireRouting
 */
class RouterTest extends PHPUnitTestCase {

    /**
     *
     */
    public function testBasicIntegrationWithMatchingStrategy() {
        $Bag = $this->getMock('\SprayFire\Routing\RouteBag');
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Route = $this->getMock('\SprayFire\Routing\Route');
        $Route->expects($this->once())
              ->method('getControllerNamespace')
              ->will($this->returnValue('SprayFire.Controller.FireController'));
        $Route->expects($this->once())
              ->method('getControllerClass')
              ->will($this->returnValue('Pages'));
        $Route->expects($this->once())
              ->method('getAction')
              ->will($this->returnValue('index'));
        $Strategy = $this->getMock('\SprayFire\Routing\MatchStrategy');
        $Strategy->expects($this->once())
                 ->method('getRouteAndParameters')
                 ->with($Bag, $Request)
                 ->will($this->returnValue(array('Route' => $Route, 'parameters' => array())));

        $Normalizer = $this->getMock('\SprayFire\Routing\FireRouting\Normalizer');
        $Normalizer->expects($this->never())
                   ->method('normalizeController');
        $Normalizer->expects($this->never())
                   ->method('normalizeAction');
        $Router = new FireRouting\Router($Strategy, $Bag, $Normalizer);
        $RoutedRequest = $Router->getRoutedRequest($Request);

        $expected = 'SprayFire.Controller.FireController.Pages';

        $this->assertSame($expected, $RoutedRequest->getController());
    }

    public function testEnsuringRoutedRequestsAreCachedAppropriately() {
        $Bag = $this->getMock('\SprayFire\Routing\RouteBag');
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Route = $this->getMock('\SprayFire\Routing\Route');
        $Route->expects($this->once())
            ->method('getControllerNamespace')
            ->will($this->returnValue('SprayFire.Controller.FireController'));
        $Route->expects($this->once())
            ->method('getControllerClass')
            ->will($this->returnValue('Pages'));
        $Route->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('index'));
        $Strategy = $this->getMock('\SprayFire\Routing\MatchStrategy');
        $Strategy->expects($this->once())
            ->method('getRouteAndParameters')
            ->with($Bag, $Request)
            ->will($this->returnValue(array('Route' => $Route, 'parameters' => array())));

        $Normalizer = $this->getMock('\SprayFire\Routing\FireRouting\Normalizer');
        $Normalizer->expects($this->never())
            ->method('normalizeController');
        $Normalizer->expects($this->never())
            ->method('normalizeAction');
        $Router = new FireRouting\Router($Strategy, $Bag, $Normalizer);
        $RoutedRequest = $Router->getRoutedRequest($Request);

        $expected = 'SprayFire.Controller.FireController.Pages';
        $this->assertSame($expected, $RoutedRequest->getController());

        $secondOne = $Router->getRoutedRequest($Request);
        $this->assertSame($secondOne, $RoutedRequest);
    }

    public function testEnsuringControllerIsNormalizedProperly() {
        $Bag = $this->getMock('\SprayFire\Routing\RouteBag');
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Route = $this->getMock('\SprayFire\Routing\Route');
        $Route->expects($this->once())
            ->method('getControllerNamespace')
            ->will($this->returnValue('SprayFire.Controller.FireController'));
        $Route->expects($this->once())
            ->method('getControllerClass')
            ->will($this->returnValue('test_pages'));
        $Route->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('index'));
        $Strategy = $this->getMock('\SprayFire\Routing\MatchStrategy');
        $Strategy->expects($this->once())
            ->method('getRouteAndParameters')
            ->with($Bag, $Request)
            ->will($this->returnValue(array('Route' => $Route, 'parameters' => array())));

        $Normalizer = $this->getMock('\SprayFire\Routing\FireRouting\Normalizer');
        $Normalizer->expects($this->once())
            ->method('normalizeController')
            ->with('test_pages')
            ->will($this->returnValue('TestPages'));
        $Normalizer->expects($this->never())
            ->method('normalizeAction');
        $Router = new FireRouting\Router($Strategy, $Bag, $Normalizer);
        $RoutedRequest = $Router->getRoutedRequest($Request);

        $expected = 'SprayFire.Controller.FireController.TestPages';

        $this->assertSame($expected, $RoutedRequest->getController());
    }

    public function testNormalizingActionProperly() {
        $Bag = $this->getMock('\SprayFire\Routing\RouteBag');
        $Request = $this->getMock('\SprayFire\Http\Request');
        $Route = $this->getMock('\SprayFire\Routing\Route');
        $Route->expects($this->once())
            ->method('getControllerNamespace')
            ->will($this->returnValue('SprayFire.Controller.FireController'));
        $Route->expects($this->once())
              ->method('getControllerClass')
              ->will($this->returnValue('Pages'));
        $Route->expects($this->once())
              ->method('getAction')
              ->will($this->returnValue('index_yo_dog'));
        $Strategy = $this->getMock('\SprayFire\Routing\MatchStrategy');
        $Strategy->expects($this->once())
                 ->method('getRouteAndParameters')
                 ->with($Bag, $Request)
                 ->will($this->returnValue(array('Route' => $Route, 'parameters' => array())));

        $Normalizer = $this->getMock('\SprayFire\Routing\FireRouting\Normalizer');
        $Normalizer->expects($this->never())
                   ->method('normalizeController');
        $Normalizer->expects($this->once())
                   ->method('normalizeAction')
                   ->with('index_yo_dog')
                   ->will($this->returnValue('indexYoDog'));
        $Router = new FireRouting\Router($Strategy, $Bag, $Normalizer);
        $RoutedRequest = $Router->getRoutedRequest($Request);

        $this->assertSame('SprayFire.Controller.FireController.Pages', $RoutedRequest->getController());
        $this->assertSame('indexYoDog', $RoutedRequest->getAction());
    }


}
