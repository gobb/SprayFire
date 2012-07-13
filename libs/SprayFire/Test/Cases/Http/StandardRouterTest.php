<?php

/**
 * A test of the StandardRouter implementation to ensure it routes as expected
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Cases\Http;

class StandardRouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * Holds a single copy of the Normalizer object to prevent unnecessary object
     * instances from being created.
     *
     * @property SprayFire.Http.Routing.Normalizer
     */
    protected static $Normalizer;

    /**
     * Ensures that the Normalizer instance has been created for creating a Router
     * object
     */
    public function setUp() {
        if (self::$Normalizer === null) {
            self::$Normalizer = new \SprayFire\Http\Routing\Normalizer();
        }
    }

    /**
     * Assures that the Router will properly use the controller, action and parameters
     * given by the request if there is no routing available.
     */
    public function testStandardRouterWithGivenControllerActionAndParamsNotRouted() {
        $Request = $this->getRequest('/SprayFire/controller/action/param1/param2/param3/');
        $Router = $this->getRouter('SprayFire');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayTest', $RoutedRequest->getAppNamespace());
        $this->assertSame('SprayTest.Controller.Controller', $RoutedRequest->getController());
        $this->assertSame('action', $RoutedRequest->getAction());
        $this->assertSame(array('param1', 'param2', 'param3'), $RoutedRequest->getParameters());
    }

    /**
     * Assures that the Router will properly use default values in configuration
     * if there are none present in the request.
     */
    public function testStandardRouterWithNoControllerActionOrParamsNotRouted() {
        $Request = $this->getRequest('/SprayFire/');
        $Router = $this->getRouter('SprayFire');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('SprayTest', $RoutedRequest->getAppNamespace());
        $this->assertSame('SprayTest.Controller.TestPages', $RoutedRequest->getController());
        $this->assertSame('indexYoDog', $RoutedRequest->getAction());
        $this->assertSame(array('yo', 'dog'), $RoutedRequest->getParameters());
    }

    /**
     * Assures that the Router can route a request when only the controller name
     * determines the routing
     */
    public function testStandardRouterWithRoutedByControllerOnly() {
        $Request = $this->getRequest('/love.game/charles/loves/dyana');
        $Router = $this->getRouter('love.game');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('YoDog', $RoutedRequest->getAppNamespace());
        $this->assertSame('YoDog.Controller.RollTide', $RoutedRequest->getController());
        $this->assertSame('roll', $RoutedRequest->getAction());
        $this->assertSame(array('dyana'), $RoutedRequest->getParameters());
    }

    /**
     * Assures that the router will route a request when both a controller name
     * and action are determining the routing.
     */
    public function testStandardRouterWithRoutedbyControllerAndAction() {
        $Request = $this->getRequest('/college.football/charles/roots_for/alabama');
        $Router = $this->getRouter('college.football');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('FourteenChamps', $RoutedRequest->getAppNamespace());
        $this->assertSame('FourteenChamps.Controller.NickSaban', $RoutedRequest->getController());
        $this->assertSame('win', $RoutedRequest->getAction());
        $this->assertSame(array('alabama'), $RoutedRequest->getParameters());
    }

    /**
     * Ensuring that a request will use values provided but the appropriate namespace
     * is used if routed.
     */
    public function testStandardRouterWithOnlyNamespaceRouted() {
        $Request = $this->getRequest('/brewmaster/charles/drinks/sam_adams');
        $Router = $this->getRouter('brewmaster');
        $RoutedRequest = $Router->getRoutedRequest($Request);
        $this->assertInstanceOf('\\SprayFire\\Http\\Routing\\RoutedRequest', $RoutedRequest);
        $this->assertSame('FavoriteBrew', $RoutedRequest->getAppNamespace());
        $this->assertSame('FavoriteBrew.Controller.Charles', $RoutedRequest->getController());
        $this->assertSame('drinks', $RoutedRequest->getAction());
        $this->assertSame(array('sam_adams'), $RoutedRequest->getParameters());
    }

    /**
     * @param string $requestUri
     * @return SprayFire.Http.StandardRequest
     */
    protected function getRequest($requestUri) {
        $_server = array();
        $_server['REQUEST_URI'] = $requestUri;
        $Uri = new \SprayFire\Http\ResourceIdentifier($_server);
        $Headers = new \SprayFire\Http\StandardRequestHeaders($_server);
        return new \SprayFire\Http\StandardRequest($Uri, $Headers);
    }

    /**
     *
     * @param string $installDir
     * @return SprayFire.Http.Routing.StandardRouter
     */
    protected function getRouter($installDir) {
        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/config/SprayFire/routes.json';
        return new \SprayFire\Http\Routing\StandardRouter(self::$Normalizer, $configPath, $installDir);
    }

}
