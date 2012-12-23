<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Test\Cases\Controller\FireController;

use \SprayFire\Responder as SFResponder,
    \SprayFire\Controller\FireController as FireController;

use \PHPUnit_Framework_TestCase as PHPUnitTestCase;

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


}

class BaseHelper extends FireController\Base {

}
