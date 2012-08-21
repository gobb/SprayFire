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

class HtmlResponderTest extends \PHPUnit_Framework_TestCase {

    protected $JavaNameConverter;

    public function setUp() {
        $this->JavaNameConverter = new \SprayFire\JavaNamespaceConverter();
    }

    public function testSanitizingHtmlData() {
        $Responder = new \SprayFire\Responder\FireResponder\Html();
        $dirtyData = array(
            'var1' => '<script>alert(\'Yo dog, I stole your focus.\');</script>',
            'var2' => 'Some seemingly \'innocent\' text <b>but</b> still has HTML & an "ampersand" in it',
            'var3' => 'Testing that &lt; and &gt; do not get encoded'
        );
        $cleanData = $Responder->sanitizeData($dirtyData);
        $expected = array(
            'var1' => '&lt;script&gt;alert(\'Yo dog, I stole your focus.\');&lt;/script&gt;',
            'var2' => 'Some seemingly \'innocent\' text &lt;b&gt;but&lt;/b&gt; still has HTML &amp; an &quot;ampersand&quot; in it',
            'var3' => 'Testing that &lt; and &gt; do not get encoded'
        );
        $this->assertSame($expected, $cleanData);
    }

    public function testGeneratingValidResponseWithoutData() {
        $install = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework';
        $RootPaths = new \SprayFire\FileSys\FireFileSys\RootPaths($install);
        $Paths = new \SprayFire\FileSys\FireFileSys\Paths($RootPaths);
        $Cache = new \SprayFire\ReflectionCache($this->JavaNameConverter);
        $Container = new \SprayFire\Service\FireService\Container($Cache);
        $EmergencyLogger = $DebugLogger = $InfoLogger = new \SprayFire\Logging\NullLogger();
        $ErrorLogger = new \SprayFire\Test\Helpers\DevelopmentLogger();
        $LogDelegator = new \SprayFire\Logging\FireLogging\LogDelegator($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
        $Container->addService($Paths, null);

        $ControllerFactory = new \SprayFire\Controller\FireController\Factory($Cache, $Container, $LogDelegator);
        $Controller = $ControllerFactory->makeObject('SprayFire.Test.Cases.Responder.FireResponder.NoDataController');

        $this->assertInstanceOf('\\SprayFire\\Test\\Cases\\Responder\\FireResponder\\NoDataController', $Controller);
        $Controller->index();

        $Responder = new \SprayFire\Responder\FireResponder\Html();
        $response = $Responder->generateDynamicResponse($Controller);
        $this->assertSame('<div>SprayFire</div>', $response);
    }

    public function testGeneratingStaticResponse() {
        $layoutPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/libs/SprayFire/Responder/html/layout/just-templatecontents-around-div.php';
        $templatePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/libs/SprayFire/Responder/html/just-sprayfire.php';
        $Responder = new \SprayFire\Responder\FireResponder\Html();
        $response = $Responder->generateStaticResponse($layoutPath, $templatePath);
        $this->assertSame('<div>SprayFire</div>', $response);
    }

    public function testGeneratingStaticResponseWithInvalidFile() {
        $Responder = new \SprayFire\Responder\FireResponder\Html();
        $this->setExpectedException('\\SprayFire\\Exception\\ResourceNotFound');
        $layoutPath = '';
        $templatePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/libs/SprayFire/Responder/html/just-sprayfire.php';
        $Responder->generateStaticResponse($layoutPath, $templatePath);
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