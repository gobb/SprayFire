<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFireTest\Controller\FireController;

use \SprayFire\Responder as SFResponder,
    \SprayFire\Controller\FireController as FireController,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Controller.FireController
 */
class BaseTest extends PHPUnitTestCase {

    public function testSettingAndGettingSingleDataInAppropriateContexts() {
        $htmlContentData = 'this is HTML content';
        $htmlAttributeData = 'this goes in an HTML attribute';
        $cssData = 'this would be in some dynamic css';
        $javaScriptData = 'this is gonna be some script stuff';

        $BaseHelper = new BaseHelper();
        $BaseHelper->setResponderData('htmlContent', $htmlContentData, SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT);
        $BaseHelper->setResponderData('htmlAttribute', $htmlAttributeData, SFResponder\OutputEscaper::HTML_ATTRIBUTE_CONTEXT);
        $BaseHelper->setResponderData('css', $cssData, SFResponder\OutputEscaper::CSS_CONTEXT);
        $BaseHelper->setResponderData('javascript', $javaScriptData, SFResponder\OutputEscaper::JAVASCRIPT_CONTEXT);

        $expectedHtmlContent = array(
            'htmlContent' => $htmlContentData
        );
        $expectedHtmlAttribute = array(
            'htmlAttribute' => $htmlAttributeData
        );
        $expectedCss = array(
            'css' => $cssData
        );
        $expectedJavaScript = array(
            'javascript' => $javaScriptData
        );

        $this->assertSame($expectedHtmlContent, $BaseHelper->getResponderData(SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT));
        $this->assertSame($expectedHtmlAttribute, $BaseHelper->getResponderData(SFResponder\OutputEscaper::HTML_ATTRIBUTE_CONTEXT));
        $this->assertSame($expectedCss, $BaseHelper->getResponderData(SFResponder\OutputEscaper::CSS_CONTEXT));
        $this->assertSame($expectedJavaScript, $BaseHelper->getResponderData(SFResponder\OutputEscaper::JAVASCRIPT_CONTEXT));
    }

    public function testSettingMultipleResponderDataInOneContext() {
        $data = array(
            'foo' => 'bar',
            'bar' => 'foo',
            'sprayfire' => 'framework'
        );
        $BaseHelper = new BaseHelper();
        $BaseHelper->setMultipleResponderData($data);

        $this->assertSame($data, $BaseHelper->getResponderData());
    }

    public function testGettingDefaultResponderName() {
        $BaseHelper = new BaseHelper();
        $this->assertSame('SprayFire.Responder.FireResponder.Html', $BaseHelper->getResponderName());
    }

    public function testEnsureProperServiceReturnedForTemplateManager() {
        $BaseHelper = new BaseHelper();
        $StdClass = new \stdClass();
        $BaseHelper->giveService('TemplateManager', $StdClass);

        $this->assertSame($StdClass, $BaseHelper->getTemplateManager());
    }

}

class BaseHelper extends FireController\Base {

}
