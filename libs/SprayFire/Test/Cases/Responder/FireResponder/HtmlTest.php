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

use \SprayFire\Responder\FireResponder as FireResponder;

class HtmlTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that the Layout
     */
    public function testGeneratingValidResponseWithoutDataAndNoContentTemplates() {
        $Responder = new FireResponder\Html();

        $LayoutTemplate = $this->getMock('\SprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                           'Responder' => $Responder
                       ))
                       ->will($this->returnValue('<div>SprayFire</div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
                        ->method('getLayoutTemplate')
                        ->will($this->returnValue($LayoutTemplate));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));

        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();

        $expected = '<div>SprayFire</div>';

        $this->assertSame($expected, $actual);
    }

    public function testGenerateValidResponseWithNoDataButWithContentTemplates() {
        $Responder = new FireResponder\Html();

        $ContentTemplate = $this->getMock('\SprayFire\Responder\Template\Template');
        $ContentTemplate->expects($this->once())
                        ->method('getContent')
                        ->with(array(
                            'Responder' => $Responder
                        ))
                        ->will($this->returnValue('<p>Template content</p>'));

        $LayoutTemplate = $this->getMock('\SprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                            'Responder' => $Responder,
                            'templateContent' => '<p>Template content</p>'
                       ))
                       ->will($this->returnValue('<div><p>Template content</p></div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
                        ->method('getLayoutTemplate')
                        ->will($this->returnValue($LayoutTemplate));
        $TemplateManager->expects($this->once())
                        ->method('getContentTemplates')
                        ->will($this->returnValue(array(
                            'templateContent' => $ContentTemplate
                        )));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));

        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();

        $expected = '<div><p>Template content</p></div>';
        $this->assertSame($expected, $actual);

    }



}