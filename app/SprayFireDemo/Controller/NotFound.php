<?php

/**
 * A concrete implementation of FireController\Base that will be used if the
 * resource requested could not be found.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFireDemo\Controller;

use \SprayFire\Controller\FireController as FireController,
    \SprayFire\Responder\Template\FireTemplate as FireTemplate;

/**
 * @package SprayFireDemo
 * @subpackage Controller
 */
class NotFound extends FireController\Base {

    /**
     * Sets up the default layout and the 404.php template to be sent back to
     * the user.
     *
     * @return void
     */
    public function index() {
        $layoutFilePath = $this->Paths->getAppPath('SprayFireDemo/templates/layout/default.php');
        $LayoutTemplate = new FireTemplate\FileTemplate('layoutTemplate', $layoutFilePath);
        $this->TemplateManager->setLayoutTemplate($LayoutTemplate);

        $path = $this->Paths->getAppPath('SprayFireDemo/templates/404.php');
        $Template = new FireTemplate\FileTemplate('templateContent', $path);
        $this->TemplateManager->addContentTemplate($Template);
    }

}
