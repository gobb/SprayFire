<?php

/**
 * Implementation of \SprayFire\Controller\Controller that is used to show off the
 * installation and debug pages provided in the default SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFireDemo\Controller;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\Controller\FireController as FireController,
    \SprayFire\Responder\Template\FireTemplate as FireTemplate;

/**
 * This controller is responsible for the default SprayFire install pages, other
 * than the about page.
 *
 * This controller is not intended to be used by your application.  Please extend
 * your controllers on some application specific controller.  The default SprayFire
 * install should include an implementation in:
 *
 * \<AppName>\Controller\Base
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
class Pages extends FireController\Base {

    /**
     * Ensures that the default layout template is set in the $TemplateManager
     *
     * @param \SprayFire\Mediator\Event $Event
     */
    public function beforeAction(SFMediator\Event $Event) {
        parent::beforeAction($Event);
        $layoutFilePath = $this->Paths->getAppPath('SprayFireDemo/templates/layout/default.php');
        $LayoutTemplate = new FireTemplate\FileTemplate('layoutTemplate', $layoutFilePath);
        $this->TemplateManager->setLayoutTemplate($LayoutTemplate);
    }

    /**
     * Provides data used by the default SprayFire install home page; primarily
     * responsible for setting up the appropriate sidebar and providing sidebar
     * content.
     */
    public function index() {
        $templateFilePath = $this->Paths->getAppPath('SprayFireDemo/templates/index.php');
        $ContentTemplate = new FireTemplate\FileTemplate('templateContent', $templateFilePath);
        $this->TemplateManager->addContentTemplate($ContentTemplate);

        $sidebarTemplateFilePath = $this->Paths->getAppPath('SprayFireDemo/templates/sidebar.php');
        $SidebarTemplate = new FireTemplate\FileTemplate('sidebarContent', $sidebarTemplateFilePath);
        $this->TemplateManager->addContentTemplate($SidebarTemplate);

        $csprayGravatarHash = '0fd2816e78f6a04d5f8ce0aba1cb42e6';
        $dyanaGravatarHash = 'c1ca92616de3b725e808fb69a6bf94d2';

        $this->setMultipleResponderData(\compact(
            'csprayGravatarHash',
            'dyanaGravatarHash'
        ));
    }

    /**
     * Provides some basic data about the request, nothing too detailed and just
     * dumps that information out.
     */
    public function debug() {
        $templateFilePath = $this->Paths->getAppPath('SprayFireDemo/templates/debug-content.php');
        $ContentTemplate = new FireTemplate\FileTemplate('templateContent', $templateFilePath);
        $this->TemplateManager->addContentTemplate($ContentTemplate);

        $serverData = \print_r($_SERVER, true);
        $sessionActive = (\session_id() === '') ? 'No' : 'Yes';
        $sessionData = '';
        if (isset($_SESSION)) {
            $sessionData = \print_r($_SESSION, true);
        }
        $postData = \print_r($_POST, true);
        $getData = \print_r($_GET, true);
        $controller = \get_class();
        $action = $this->service('RoutedRequest')->getAction();
        $parameters = \print_r(\func_get_args(), true);

        $this->setMultipleResponderData(\compact(
            'serverData',
            'sessionActive',
            'sessionData',
            'postData',
            'getData',
            'controller',
            'action',
            'parameters'
        ));
    }

}
