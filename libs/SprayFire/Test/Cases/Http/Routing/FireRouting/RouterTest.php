<?php

/**
 * A test of the StandardRouter implementation to ensure it routes as expected
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Http\Routing\FireRouting;

use \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Http\Routing\FireRouting as FireRouting;

class RouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * Holds a single copy of the Normalizer object to prevent unnecessary object
     * instances from being created.
     *
     * @property SprayFire.Http.Routing.Normalizer
     */
    protected $Normalizer;

    /**
     * @property array
     */
    protected $routesConfig;

    /**
     * @property string
     */
    protected $mockFrameworkPath;

    /**
     * Ensures that the Normalizer instance has been created for creating a Router
     * object
     */
    public function setUp() {
        $this->Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
        $this->routesConfig = array();
        $this->mockFrameworkPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework';
        $this->setUpRoutesConfig();
    }

    protected function setUpRoutesConfig() {
        $this->routesConfig['404'] = array(
            'static' => true,
            'layoutPath' => 'layout',
            'templatePath' => 'template'
        );

        $this->routesConfig['routes'] = array(
            '/' => array(
                'namespace' => 'SprayFire.Test.Helpers.Controller',
                'controller' => 'TestPages',
                'action' => 'indexYoDog',
                'parameters' => array(
                    'yo',
                    'dog'
                )
            ),
            '/charles/roots_for/(?P<item>[A-Za-z]+)/' => array(
                'namespace' => 'FourteenChamps.Controller',
                'controller' => 'NickSaban',
                'action' => 'win'
            ),
            '/charles/drinks/(?P<brewer>[A-Za-z_]+)/(?P<beer>[A-Za-z_]+)/' => array(
                'namespace' => 'FavoriteBrew.Controller',
                'controller' => 'Charles',
                'action' => 'drinks'
            ),
            '/should/be/post/' => array(
                'method' => 'POST',
                'controller' => 'post-method',
                'action' => 'createSomething'
            )
        );
    }

    /**
     * Assures that the Router will properly use default values in configuration
     * if there are none present in the request.
     */
    public function testStandardRouterGoingToRootPath() {
        $Request = $this->getRequest('/SprayFire/');

        $RouteBag = new FireRouting\RouteBag();
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())->method('getPattern')->will($this->returnValue('/'));
        $MockRoute->expects($this->once())->method('getMethod')->will($this->returnValue(null));
        $MockRoute->expects($this->once())->method('getControllerNamespace')->will($this->returnValue('SprayFire.Test.Helpers.Controller'));
        $MockRoute->expects($this->once())->method('getControllerClass')->will($this->returnValue('TestPages'));
        $MockRoute->expects($this->once())->method('getAction')->will($this->returnValue('index-yo-dog'));
        $RouteBag->addRoute($MockRoute);

        $Router = new FireRouting\Router($RouteBag, $this->Normalizer, 'SprayFire');

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
        $Request = $this->getRequest('/college.football/charles/roots_for/alabama');

        $RouteBag = new FireRouting\RouteBag();
        $MockRoute = $this->getMock('\\SprayFire\\Http\\Routing\\Route');
        $MockRoute->expects($this->once())->method('getPattern')->will($this->returnValue('/charles/roots_for/(?P<item>[A-Za-z]+)/'));
        $MockRoute->expects($this->once())->method('getMethod')->will($this->returnValue(null));
        $MockRoute->expects($this->once())->method('getControllerNamespace')->will($this->returnValue('FourteenChamps.Controller'));
        $MockRoute->expects($this->once())->method('getControllerClass')->will($this->returnValue('NickSaban'));
        $MockRoute->expects($this->once())->method('getAction')->will($this->returnValue('win'));
        $RouteBag->addRoute($MockRoute);

        $Router = new FireRouting\Router($RouteBag, $this->Normalizer, 'college.football');
        $RoutedRequest = $Router->getRoutedRequest($Request);
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
        $Request = $this->getRequest('/brewmaster/charles/drinks/sam_adams/boston_lager');
        $Router = $this->getRouter('brewmaster');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('FavoriteBrew', $RoutedRequest->getAppNamespace());
        $this->assertSame('FavoriteBrew.Controller.Charles', $RoutedRequest->getController());
        $this->assertSame('drinks', $RoutedRequest->getAction());
        $this->assertSame(array('brewer' => 'sam_adams', 'beer' => 'boston_lager'), $RoutedRequest->getParameters());
    }

    public function testGettingSameRoutedRequestFromSameRequest() {
        $Request = $this->getRequest('');
        $Router = $this->getRouter('');
        $RoutedRequestOne = $Router->getRoutedRequest($Request);
        $RoutedRequestTwo = $Router->getRoutedRequest($Request);
        $this->assertSame($RoutedRequestOne, $RoutedRequestTwo);
    }

    public function testValidRouteWithImproperMethod() {
        $Request = $this->getRequest('/should/be/post', 'POST');
        $Router = $this->getRouter('');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $expectedController = 'SprayFire.Controller.FireController.PostMethod';
        $expectedAction = 'createSomething';
        $this->assertFalse($RoutedRequest->isStatic(), 'The RoutedRequest is static, although it should not be');
        $this->assertSame($expectedController, $RoutedRequest->getController());
        $this->assertSame($expectedAction, $RoutedRequest->getAction());
    }

    public function testGettingRouter404RoutedRequest() {
        $Router = $this->getRouter('');
        $Routed404Request = $Router->get404RoutedRequest();
        $this->assertTrue($Routed404Request->isStatic(), '404 RoutedRequest is not static but should be');
        $staticFiles = $Router->getStaticFilePaths($Routed404Request);
        $this->assertSame('layout', $staticFiles['layoutPath']);
        $this->assertSame('template', $staticFiles['templatePath']);
    }

    public function testGetting404RoutedRequestAfterConfiguration() {
        $Router = $this->getRouter('');
        $BeforeRoutedRequest = $Router->get404RoutedRequest();
        $this->assertTrue($BeforeRoutedRequest->isStatic(), '404 Routed Request is not static');
        $Router->set404Configuration(array(
            'static' => false,
            'namespace' => 'SprayFire.Controller',
            'controller' => 'NullController',
            'action' => 'index'
        ));
        $RoutedRequest = $Router->get404RoutedRequest();
        $this->assertFalse($RoutedRequest->isStatic(), 'RoutedRequest is static ');
        $this->assertSame('SprayFire.Controller.NullController', $RoutedRequest->getController());
        $this->assertSame('index', $RoutedRequest->getAction());
    }

    /**
     * @param string $requestUri
     * @return SprayFire.Http.StandardRequest
     */
    protected function getRequest($requestUri, $method = 'GET') {
        $MockUri = $this->getMock('\\SprayFire\\Http\\Uri');
        $MockUri->expects($this->once())->method('getPath')->will($this->returnValue($requestUri));

        $MockRequest = $this->getMock('\\SprayFire\\Http\\Request');
        $MockRequest->expects($this->once())->method('getUri')->will($this->returnValue($MockUri));
        $MockRequest->expects($this->once())->method('getMethod')->will($this->returnValue($method));
        return $MockRequest;
    }

    /**
     *
     * @param string $installDir
     * @return SprayFire.Http.Routing.StandardRouter
     */
    protected function getRouter($installDir) {
        return new \SprayFire\Http\Routing\FireRouting\Router($this->Normalizer, $this->routesConfig, $installDir);
    }

}
