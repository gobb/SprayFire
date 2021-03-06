<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Responder\Template\FireTemplate;

use \SprayFire\Responder\Template\FireTemplate as FireTemplate;

/**
 *
 * @package SprayFireTest
 * @subpackage Responder.FireResponder.FireTemplate
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

        $Manager = new FireTemplate\Manager();

        $Manager->addContentTemplate($Template);
        $this->assertTrue($Manager->hasTemplate('name'));
    }

    /**
     * Ensures that if we check for a Manager having a template that is not yet
     * added false is properly returned.
     */
    public function testManagerNotHavingTemplate() {
        $Manager = new FireTemplate\Manager();

        $this->assertFalse($Manager->hasTemplate('noExist'));
    }

    /**
     * Ensures that a layout template can be properly set and retrieved.
     */
    public function testManagerSettingAndRetrievingLayoutTemplate() {
        $Template = $this->getMock('\SprayFire\Responder\Template\Template');

        $Manager = new FireTemplate\Manager();
        $Manager->setLayoutTemplate($Template);

        $this->assertSame($Template, $Manager->getLayoutTemplate());
    }

    /**
     * Ensures that an exception is thrown if a layout template is attempted to
     * be retrieved that has not been set yet.
     */
    public function testManagerRetrievingLayoutTemplateNotSet() {
        $Manager = new FireTemplate\Manager();
        $this->setExpectedException('\SprayFire\Responder\Template\Exception\LayoutNotSet');
        $LayoutTemplate = $Manager->getLayoutTemplate();
    }

    /**
     * Ensures that a series of templates added to the managare are retrieved
     * with the appropriate keys.
     */
    public function testManagerGettingContentTemplates() {
        $TemplateOne = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateOne->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('templateOne'));
        $TemplateTwo = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateTwo->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('templateTwo'));

        $Manager = new FireTemplate\Manager();
        $Manager->addContentTemplate($TemplateOne);
        $Manager->addContentTemplate($TemplateTwo);

        $expected = array(
            'templateOne' => $TemplateOne,
            'templateTwo' => $TemplateTwo
        );
        $this->assertSame($expected, $Manager->getContentTemplates());
    }

    /**
     * Ensures that getContentTemplates() will appropriately only return content
     * templates and not the layout template.
     */
    public function testManagerGettingOnlyContentTemplatesWithLayoutSet() {
        $TemplateOne = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateOne->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('content_one'));
        $TemplateTwo = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateTwo->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('content_two'));

        $LayoutTemplate = $this->getMock('\SprayFire\Responder\Template\Template');

        $Manager = new FireTemplate\Manager();
        $Manager->setLayoutTemplate($LayoutTemplate);
        $Manager->addContentTemplate($TemplateOne);
        $Manager->addContentTemplate($TemplateTwo);

        $expected = array(
            'content_one' => $TemplateOne,
            'content_two' => $TemplateTwo
        );
        $this->assertSame($expected, $Manager->getContentTemplates());
    }

    /**
     * Ensures that a template added is also properly removed.
     */
    public function testManagerRemovingTemplateThatHasBeenAdded() {
        $TemplateOne = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateOne->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('one'));

        $Manager = new FireTemplate\Manager();
        $Manager->addContentTemplate($TemplateOne);

        $this->assertTrue($Manager->hasTemplate('one'), 'The Manager does not have the appropriate template');
        $this->assertTrue($Manager->removeTemplate('one'), 'The Manager did not return proper value for removing template');
        $this->assertFalse($Manager->hasTemplate('one'), 'The Manager still has a template that should be removed');
    }

    /**
     * Ensures that a template attempted to be removed that has not been added
     * returns the appropriate value.
     */
    public function testManagerRemovingTemplateThatHasNotBeenAdded() {
        $TemplateOne = $this->getMock('\SprayFire\Responder\Template\Template');
        $TemplateOne->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('one'));

        $Manager = new FireTemplate\Manager();
        $Manager->addContentTemplate($TemplateOne);

        $this->assertTrue($Manager->hasTemplate('one'), 'The Manager does not have the appropriate template');
        $this->assertFalse($Manager->removeTemplate('two'), 'The Manager did not return proper value for removing template');
        $this->assertTrue($Manager->hasTemplate('one'), 'The Manager does not have a template that should still be there');
    }

}
