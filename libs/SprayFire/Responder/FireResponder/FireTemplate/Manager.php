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

namespace SprayFire\Responder\FireResponder\FireTemplate;

use \SprayFire\Responder\Template as SFResponderTemplate,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * @package SprayFire
 * @subpackage`Responder.FireResponder.FireTemplate
 */
class Manager extends SFCoreObject implements SFResponderTemplate\Manager {

    /**
     * Stores the templates added to the Manager
     *
     * @property array
     */
    protected $templates = array();

    /**
     * Stores a $Template against the name returned from $Template::getName.
     *
     * @param SprayFire.Responder.Template.Template $Template
     * @return void
     */
    public function addContentTemplate(SFResponderTemplate\Template $Template) {
        $this->templates[(string) $Template->getName()] = $Template;
    }

    /**
     * Provides an array of SprayFire.Responder.Template.Template that have been
     * added as a content template.
     *
     * @return array
     */
    public function getContentTemplates() {
        return $this->templates;
    }

    public function getLayoutTemplate() {

    }

    /**
     * Returns whether or not a specific template has been added to this instance.
     *
     * @param string $templateName
     * @return boolean
     */
    public function hasTemplate($templateName) {
        return \array_key_exists($templateName, $this->templates);
    }

    public function removeTemplate($templateName) {

    }

    public function setLayoutTemplate(SFResponderTemplate\Template $Template) {

    }

}