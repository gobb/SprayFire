<?php

/**
 * Default implementation of SprayFire.Responder.Template.Manager to provide layout
 * and content templates to the SprayFire.Responder.Responder generating the
 * response for a given Request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\Template\FireTemplate;

use \SprayFire\Responder\Template as SFResponderTemplate,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * @package SprayFire
 * @subpackage`Responder.FireResponder.FireTemplate
 */
class Manager extends SFCoreObject implements SFResponderTemplate\Manager {

    /**
     * Represents the primary template for a given response.
     *
     * @property SprayFire.Responder.Template.Template
     */
    protected $LayoutTemplate;

    /**
     * Stores the templates added to the Manager
     *
     * @property array
     */
    protected $contentTemplates = array();

    /**
     * Stores a $Template against the name returned from $Template::getName.
     *
     * @param SprayFire.Responder.Template.Template $Template
     * @return void
     */
    public function addContentTemplate(SFResponderTemplate\Template $Template) {
        $this->contentTemplates[(string) $Template->getName()] = $Template;
    }

    /**
     * Provides an array of SprayFire.Responder.Template.Template that have been
     * added as a content template.
     *
     * @return array
     */
    public function getContentTemplates() {
        return $this->contentTemplates;
    }

    /**
     * Return the SprayFire.Responder.Template.Template that was passed to
     * Manager::setLayoutTemplate().
     *
     * If Manager::setLayoutTemplate is never called and this method is invoked
     * an exception will be thrown.
     *
     * @return SprayFire.Responder.Template.Template
     * @throws SprayFire.Responder.Template.Exception.LayoutNotSet
     */
    public function getLayoutTemplate() {
        if (!$this->LayoutTemplate instanceof SFResponderTemplate\Template) {
            throw new SFResponderTemplate\Exception\LayoutNotSet('A layout template has not been properly set.');
        }
        return $this->LayoutTemplate;
    }

    /**
     * Returns whether or not a specific template has been added to this instance.
     *
     * @param string $templateName
     * @return boolean
     */
    public function hasTemplate($templateName) {
        return \array_key_exists($templateName, $this->contentTemplates);
    }

    /**
     * Removes a content template from the collection added, returns true if the
     * collection was removed, false if it was not.
     *
     * @param string $templateName
     * @return boolean
     */
    public function removeTemplate($templateName) {
        $hasTemplate = $this->hasTemplate($templateName);
        if (!$hasTemplate) {
            return false;
        }
        $this->contentTemplates[$templateName] = null;
        unset($this->contentTemplates[$templateName]);
        return $hasTemplate;
    }

    /**
     * @param SprayFire.Responder.Template.Template $Template
     */
    public function setLayoutTemplate(SFResponderTemplate\Template $Template) {
        $this->LayoutTemplate = $Template;
    }

}