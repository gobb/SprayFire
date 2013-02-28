<?php

/**
 * Implementation of SprayFire.Controller.Controller provided in the default SprayFire
 * install
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller as SFController,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\Responder\Template as SFResponderTemplate;


/**
 * This implementation is intended to provide the user some more detailed info on
 * SprayFire as well as its creator and contributors.
 *
 * This controller is not required to stick around, but please remove the appropriate
 * route, '/about/', if you decide to remove this from your SprayFire install.
 *
 * @package SprayFire
 * @subpackage Controller.FireController
 *
 * @property \SprayFire\FileSys\FireFileSys\Paths $Paths
 * @property \SprayFire\Http\FireHttp\Request $Request
 * @property \SprayFire\Http\Routing\FireRouting\RoutedRequest $RoutedRequest
 * @property \SprayFire\Responder\Template\FireTemplate\Manager $TemplateManager
 * @property \SprayFire\Logging\FireLogging\LogOverseer $Logging
 */
class About extends Base implements SFController\Controller {

    /**
     * Ensures that the default layout template is set in the $TemplateManager
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function beforeAction(SFMediator\Event $Event) {
        $filePath = $this->Paths->getLibsPath('SprayFire/Responder/html/layout/default.php');
        $LayoutTemplate = new SFResponderTemplate\FireTemplate\FileTemplate('layoutContent', $filePath);
        $this->TemplateManager->setLayoutTemplate($LayoutTemplate);
    }

    /**
     * Prepares data needed to let the user know more about the framework.
     *
     * @return void
     */
    public function sprayFire() {
        $templateName = 'templateContent';
        $filePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'about.php');
        $BodyTemplate = new SFResponderTemplate\FireTemplate\FileTemplate($templateName, $filePath);
        $this->TemplateManager->addContentTemplate($BodyTemplate);

        $messages = [
            'PHP 5.3 framework',
            'Developed by Charles Sprayberry',
            'Graphic Design by Dyana Stewart'
        ];
        $this->setResponderData('messages', $messages);
    }

}
