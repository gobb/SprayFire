<?php

/**
 * A test to ensure that the HtmlResponder sanitizes data correctly and produces
 * the appropriate output.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Cases\Responder\FireResponder;

class HtmlTest extends \PHPUnit_Framework_TestCase {

    protected $JavaNameConverter;

    public function setUp() {
        $this->JavaNameConverter = new \SprayFire\Utils\JavaNamespaceConverter();
    }

    public function testGeneratingValidResponseWithoutData() {
        $install = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework';
        $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($install);
        $Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);
        $Cache = new \SprayFire\Utils\ReflectionCache($this->JavaNameConverter);
        $Container = new \SprayFire\Service\FireService\Container($Cache);
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\NullLogger();
        $ErrorLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogOverseer($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $Container->addService($Paths, null);

        $ControllerFactory = new \SprayFire\Controller\FireController\Factory($Cache, $Container, $LogDelegator);
        $Controller = $ControllerFactory->makeObject('SprayFire.Test.Cases.Responder.FireResponder.NoDataController');

        $this->assertInstanceOf('\\SprayFire\\Test\\Cases\\Responder\\FireResponder\\NoDataController', $Controller);
        $Controller->index();

        $Responder = new \SprayFire\Responder\FireResponder\Html();
        $response = $Responder->generateDynamicResponse($Controller);
        $this->assertSame('<div>SprayFire</div>', $response);
    }

}

class NoDataController extends \SprayFire\Controller\FireController\Base {

    public function __construct() {
        $this->services = array(
            'Paths' => 'SprayFire.FileSys.FireFileSys.Paths'
        );
    }

    public function index() {
        $this->layoutPath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'just-templatecontents-around-div.php');
        $this->templatePath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'just-sprayfire.php');
    }
}