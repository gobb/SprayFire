<?php

/**
 * Interface responsible for allowing the storage, retrieval and meta information
 * of SprayFire.Responder.Template.Template instances that should be used in the
 * processing of a given Request.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder\Template;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Responder.Template
 */
interface Manager extends SFObject {

    /**
     * Add a SprayFire.Template.Template to the Manager to be used when generating
     * the final response to the user.
     *
     * @param \SprayFire\Responder\Template\Template $Template
     * @return void
     */
    public function setLayoutTemplate(Template $Template);

    /**
     * Return a SprayFire.Template.Template that should be used as the primary
     * layout for the response.
     *
     * @return \SprayFire\Responder\Template\Template
     */
    public function getLayoutTemplate();

    /**
     * Add a \SprayFire\Responder\Template\Template to a collection that can be
     * retrieved by Manager::getContentTemplates.
     *
     * The key used to store the $Template should be the value returned by
     * $Template::getName.
     *
     * @param \SprayFire\Responder\Template\Template $Template
     * @return void
     */
    public function addContentTemplate(Template $Template);

    /**
     * Return a collection of \SprayFire\Responder\Template\Template objects that
     * were added with addContentTemplate().
     *
     * The key for the collection should be the name of the template.
     *
     * @return Traversable
     */
    public function getContentTemplates();

    /**
     * Remove an added template that matches the $templateName.
     *
     * Return true if a template was removed, false if the $templateName has
     * not been added.
     *
     * @param string $templateName
     * @return boolean
     */
    public function removeTemplate($templateName);

    /**
     * Return whether or not a template with the given $templateName has been
     * added.
     *
     * @param string $templateName
     * @return boolean
     */
    public function hasTemplate($templateName);

}
