<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Responder\FireResponder\FireTemplate;

use \SprayFire\Responder\FireResponder\FireTemplate as FireResponderTemplate;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases.Responder.FireResponder.FireTemplate
 */
class ManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensure that when a Template with a given name is added to the Manager the
     * Manager ensures that hasTemplate() returns true.
     */
    public function testManagerAddingAndHavingTemplate() {
        $Template = $this->getMock('\SprayFire\Responder\Template\Template');
        $Template->expects($this->once())
                 ->method('getName')
                 ->will($this->returnValue('name'));

        $Manager = new FireResponderTemplate\Manager();

        $Manager->addContentTemplate($Template);
        $this->assertTrue($Manager->hasTemplate('name'));
    }

    /**
     * Ensures that if we check for a Manager having a template that is not yet
     * added false is properly returned.
     */
    public function testManagerNotHavingTemplate() {
        $Manager = new FireResponderTemplate\Manager();

        $this->assertFalse($Manager->hasTemplate('noExist'));
    }

    public function testManagerGettingContentTemplates() {
        $TemplateOne = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateOne->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('templateOne'));
        $TemplateTwo = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateTwo->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('templateTwo'));

        $Manager = new FireResponderTemplate\Manager();
        $Manager->addContentTemplate($TemplateOne);
        $Manager->addContentTemplate($TemplateTwo);

        $expected = array(
            'templateOne' => $TemplateOne,
            'templateTwo' => $TemplateTwo
        );
        $this->assertSame($expected, $Manager->getContentTemplates());
    }

}
