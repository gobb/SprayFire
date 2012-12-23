<?php

/**
 * A test to ensure that the SprayFire.Responder.FireResponder.Html implementation
 * produces the appropriate output.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Responder\FireResponder;

use \SprayFire\Responder as SFResponder,
    \SprayFire\Responder\FireResponder as FireResponder;

/**
 * @package SprayFireTest
 * @subpackage Cases.Responder.FireResponder
 */
class HtmlTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that no content templates or data from the controller properly
     * produces output from strictly the LayoutTemplate.
     */
    public function testGeneratingValidResponseWithoutDataAndNoContentTemplates() {
        $Responder = new FireResponder\Html();
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);

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
        $Controller->expects($this->once())
                   ->method('getResponderData')
                   ->will($this->returnValue(array()));

        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();

        $expected = '<div>SprayFire</div>';

        $this->assertSame($expected, $actual);
    }

    /**
     * Ensures that a TemplateManager with a single content template passes the
     * appropriate data to the LayoutTemplate.
     */
    public function testGenerateValidResponseWithNoDataButWithSingleContentTemplates() {
        $Responder = new FireResponder\Html();
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);

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
        $Controller->expects($this->once())
                   ->method('getResponderData')
                   ->will($this->returnValue(array()));

        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();

        $expected = '<div><p>Template content</p></div>';
        $this->assertSame($expected, $actual);

    }

    /**
     * Ensures that a controller with data and a TemplateManager with multiple
     * content templates results in the appropriate data passed to the LayoutTemplate.
     */
    public function testGeneratingValidResponseWithControllerDataAndMultipleContentTemplates() {
        $Responder = new FireResponder\Html();
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);

        $ContentTemplate = $this->getMock('\SprayFire\Responder\Template\Template');
        $ContentTemplate->expects($this->once())
                        ->method('getContent')
                        ->with(array(
                            'Responder' => $Responder,
                            'foo' => 'bar',
                            'bar' => 'foo'
                        ))
                        ->will($this->returnValue('<p>Template content</p>'));

        $SecondaryTemplate = $this->getMock('\SprayFire\Responder\Template\Template');
        $SecondaryTemplate->expects($this->once())
                          ->method('getContent')
                          ->with(array(
                              'Responder' => $Responder,
                              'foo' => 'bar',
                              'bar' => 'foo',
                              'templateContent' => '<p>Template content</p>'
                          ))
                          ->will($this->returnValue('<p>Secondary content</p>'));

        $LayoutTemplate = $this->getMock('\SprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                           'Responder' => $Responder,
                           'foo' => 'bar',
                           'bar' => 'foo',
                           'templateContent' => '<p>Template content</p>',
                           'sidebarContent' => '<p>Secondary content</p>'
                       ))
                       ->will($this->returnValue('<div>SprayFire</div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
                        ->method('getContentTemplates')
                        ->will($this->returnValue(array(
                            'templateContent' => $ContentTemplate,
                            'sidebarContent' => $SecondaryTemplate
                        )));
        $TemplateManager->expects($this->once())
                        ->method('getLayoutTemplate')
                        ->will($this->returnValue($LayoutTemplate));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));
        $Controller->expects($this->once())
                   ->method('getResponderData')
                   ->will($this->returnValue(array(
                       'foo' => 'bar',
                       'bar' => 'foo'
                   )));

        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();

        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $actual);
    }

    public function testAutomaticEscapingOfHtmlContentData() {
        $Responder = new FireResponder\Html();
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);

        $LayoutTemplate = $this->getMock('\sprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                            'Responder' => $Responder,
                            'greaterThan' => '&gt;',
                            'lessThan' => '&lt;',
                            'ampersand' => '&amp;'
                       ))
                       ->will($this->returnValue('<div>&gt;&lt;&amp;</div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
                        ->method('getLayoutTemplate')
                        ->will($this->returnValue($LayoutTemplate));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->once())
                   ->method('getResponderData')
                   ->with(SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT)
                   ->will($this->returnValue(array(
                        'greaterThan' => '>',
                        'lessThan' => '<',
                        'ampersand' => '&'
                   )));
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));

        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();

        $this->assertSame('<div>&gt;&lt;&amp;</div>', $actual);
    }

}
