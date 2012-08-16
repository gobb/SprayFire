<?php

/**
 * A test of the StandardRouter implementation to ensure it routes as expected
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Cases\Http\Routing\FireRouting;

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
            '/static/page/' => array(
                'static' => true,
                'layoutPath' => $this->mockFrameworkPath . '/libs/SprayFire/Responder/html/default.php',
                'templatePath' => $this->mockFrameworkPath . '/libs/SprayFire/Responder/html/template/static.php'
            ),
            '/should/be/post/' => array(
                'method' => 'POST',
                'controller' => 'post-method',
                'action' => 'createSomething'
            )
        );
    }

    /**
     * Assures that the Router will properly use the controller, action and parameters
     * given by the request if there is no routing available.
     */
    public function testStandardRouterWithUnroutedUrl() {
        $Request = $this->getRequest('/SprayFire/controller/action/param1/param2/param3/');
        $Router = $this->getRouter('SprayFire');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertTrue($RoutedRequest->isStatic(), 'A RoutedRequest is not static, though it should be.');
        $staticFiles = $Router->getStaticFilePaths($RoutedRequest);
        $this->assertSame('layout', $staticFiles['layoutPath']);
        $this->assertSame('template', $staticFiles['templatePath']);
    }

    /**
     * Assures that the Router will properly use default values in configuration
     * if there are none present in the request.
     */
    public function testStandardRouterGoingToRootPath() {
        $Request = $this->getRequest('/SprayFire/');
        $Router = $this->getRouter('SprayFire');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayFire', $RoutedRequest->getAppNamespace());
        $this->assertSame('SprayFire.Test.Helpers.Controller.TestPages', $RoutedRequest->getController());
        $this->assertSame('indexYoDog', $RoutedRequest->getAction());
        $this->assertSame(array('yo', 'dog'), $RoutedRequest->getParameters());
    }

    /**
     * Assures that the router will route a request when both a controller name
     * and action are determining the routing.
     */
    public function testStandardRouterGoingToControllerActionWithParam() {
        $Request = $this->getRequest('/college.football/charles/roots_for/alabama');
        $Router = $this->getRouter('college.football');
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

    public function testGettingStaticRoutedRequest() {
        $Request = $this->getRequest('/static/page');
        $Router = $this->getRouter('');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertTrue($RoutedRequest->isStatic());
    }

    public function testGettingStaticRoutedRequestFilePaths() {
        $Request = $this->getRequest('/static/page');
        $Router = $this->getRouter('');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $paths = $Router->getStaticFilePaths($RoutedRequest);
        $expectedLayout = $this->mockFrameworkPath . '/libs/SprayFire/Responder/html/default.php';
        $expectedTemplate = $this->mockFrameworkPath . '/libs/SprayFire/Responder/html/template/static.php';
        $actualLayout = $paths['layoutPath'];
        $actualTemplate = $paths['templatePath'];
        $this->assertSame($expectedLayout, $actualLayout);
        $this->assertSame($expectedTemplate, $actualTemplate);
    }

    public function testGettingSameRoutedRequestFromSameRequest() {
        $Request = $this->getRequest('');
        $Router = $this->getRouter('');
        $RoutedRequestOne = $Router->getRoutedRequest($Request);
        $RoutedRequestTwo = $Router->getRoutedRequest($Request);
        $this->assertSame($RoutedRequestOne, $RoutedRequestTwo);
    }

    public function testGettingSameStaticFilesFromSameRoutedRequest() {
        $Request = $this->getRequest('/static/page');
        $Router = $this->getRouter('');
        $RoutedRequestOne = $Router->getRoutedRequest($Request);
        $RoutedRequestTwo = $Router->getRoutedRequest($Request);
        $routeOneFiles = $Router->getStaticFilePaths($RoutedRequestOne);
        $routeTwoFiles = $Router->getStaticFilePaths($RoutedRequestTwo);
        $this->assertSame($routeOneFiles, $routeTwoFiles);
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

    /**
     * @param string $requestUri
     * @return SprayFire.Http.StandardRequest
     */
    protected function getRequest($requestUri, $method = 'GET') {
        $_server = array();
        $_server['REQUEST_URI'] = $requestUri;
        $_server['REQUEST_METHOD'] = $method;
        $Uri = new \SprayFire\Http\FireHttp\Uri($_server);
        $Headers = new \SprayFire\Http\FireHttp\RequestHeaders($_server);
        return new \SprayFire\Http\FireHttp\Request($Uri, $Headers, $_server);
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
