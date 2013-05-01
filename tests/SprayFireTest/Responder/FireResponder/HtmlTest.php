<?php

/**
 * A test to ensure that the \SprayFire\Responder\FireResponder\Html implementation
 * produces the appropriate output, escaped if necessary.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Responder\FireResponder;

use \SprayFire\Responder as SFResponder,
    \SprayFire\Responder\FireResponder as FireResponder,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Responder
 */
class HtmlTest extends PHPUnitTestCase {

    /**
     * Ensures that no content templates or data from the controller properly
     * produces output from strictly the LayoutTemplate.
     */
    public function testGeneratingValidResponseWithoutDataAndNoContentTemplates() {
        $Responder = $this->getHtmlResponder();

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

        $Response = $Responder->generateResponse($Controller);
        $this->assertInstanceOf('\\SprayFire\\Http\\Response', $Response);
    }

    /**
     * Ensures that a TemplateManager with a single content template passes the
     * appropriate data to the LayoutTemplate.
     */
    public function testGenerateValidResponseWithNoDataButWithSingleContentTemplates() {
        $Responder = $this->getHtmlResponder();

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

        $actual = $Responder->generateResponse($Controller);

        $expected = '<div><p>Template content</p></div>';
        $this->assertSame($expected, $actual);

    }

    /**
     * Ensures that a controller with data and a TemplateManager with multiple
     * content templates results in the appropriate data passed to the LayoutTemplate.
     */
    public function testGeneratingValidResponseWithControllerDataAndMultipleContentTemplates() {
        $Responder = $this->getHtmlResponder();

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
        $Controller->expects($this->at(1))
                   ->method('getResponderData')
                   ->will($this->returnValue(array(
                       'foo' => 'bar',
                       'bar' => 'foo'
                   )));

        $actual = $Responder->generateResponse($Controller);

        $expected = '<div>SprayFire</div>';
        $this->assertSame($expected, $actual);
    }

    public function testAutomaticEscapingOfHtmlContentData() {
        $Responder = $this->getHtmlResponder();

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
        $Controller->expects($this->at(1))
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

        $actual = $Responder->generateResponse($Controller);

        $this->assertSame('<div>&gt;&lt;&amp;</div>', $actual);
    }

    public function testAutomaticEscapingOfHtmlAttributeData() {
        $Responder = $this->getHtmlResponder();

        $LayoutTemplate = $this->getMock('\sprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                            'Responder' => $Responder,
                            'space' => '&#x20;',
                            'tab' => '&#x09;',
                            'null' => '&#xFFFD;'
                       ))
                       ->will($this->returnValue('<div>&#x20;&&#x09;&#xFFFD;</div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
            ->method('getLayoutTemplate')
            ->will($this->returnValue($LayoutTemplate));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->at(2))
                   ->method('getResponderData')
                   ->with(SFResponder\OutputEscaper::HTML_ATTRIBUTE_CONTEXT)
                   ->will($this->returnValue(array(
                        'space' => ' ',
                        'tab' => "\t",
                        'null' => "\0"
                   )));
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));

        $actual = $Responder->generateResponse($Controller);

        $this->assertSame('<div>&#x20;&&#x09;&#xFFFD;</div>', $actual);
    }

    public function testAutomaticEscapingOfCssData() {
        $Responder = $this->getHtmlResponder();

        $LayoutTemplate = $this->getMock('\sprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                            'Responder' => $Responder,
                            'comma' => '\\2C ',
                            'period' => '\\2E ',
                            'underscore' => '\\5F '
                        ))
                        ->will($this->returnValue('<div>\\2C\\2E\\5F</div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
                        ->method('getLayoutTemplate')
                        ->will($this->returnValue($LayoutTemplate));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->at(3))
                   ->method('getResponderData')
                   ->with(SFResponder\OutputEscaper::CSS_CONTEXT)
                   ->will($this->returnValue(array(
                        'comma' => ',',
                        'period' => '.',
                        'underscore' => '_'
                   )));
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));

        $actual = $Responder->generateResponse($Controller);

        $this->assertSame('<div>\\2C\\2E\\5F</div>', $actual);
    }

    public function testAutomaticEscapingOfJavaScript() {
        $Responder = $this->getHtmlResponder();

        $LayoutTemplate = $this->getMock('\sprayFire\Responder\Template\Template');
        $LayoutTemplate->expects($this->once())
                       ->method('getContent')
                       ->with(array(
                            'Responder' => $Responder,
                            'singleQuote' => '\\x27',
                            'doubleQuote' => '\\x22',
                            'ampersand' => '\\x26'
                       ))
                       ->will($this->returnValue('<div>\\x27\\x22\\x26</div>'));

        $TemplateManager = $this->getMock('\SprayFire\Responder\Template\Manager');
        $TemplateManager->expects($this->once())
                        ->method('getLayoutTemplate')
                        ->will($this->returnValue($LayoutTemplate));

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->at(4))
                   ->method('getResponderData')
                   ->with(SFResponder\OutputEscaper::JAVASCRIPT_CONTEXT)
                   ->will($this->returnValue(array(
                        'singleQuote' => "'",
                        'doubleQuote' => '"',
                        'ampersand' => '&'
                    )));
        $Controller->expects($this->once())
                   ->method('getTemplateManager')
                   ->will($this->returnValue($TemplateManager));

        $actual = $Responder->generateResponse($Controller);

        $this->assertSame('<div>\\x27\\x22\\x26</div>', $actual);
    }

    /**
     * @return \SprayFire\Responder\FireResponder\Html
     */
    protected function getHtmlResponder() {
        $Builder = $this->getMock('\SprayFire\Service\Builder');
        $Responder = new FireResponder\Html($Builder);
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);
        return $Responder;
    }

}
