<?php

/**
 * Implementation of SprayFire.Controller.Controller that is used to show off the
 * intallation and debug pages provided in the default SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

/**
 * This controller is responsible for the default SprayFire install pages, other
 * than the about page.
 *
 * This controller is not intended to be used by your application.  Please extend
 * your application controllers on some application specific controller.  The
 * default SprayFire install should include an implementation in:
 *
 * <AppName>.Controller.Base
 *
 * @package SprayFire
 * @subpackage Controller.FireController
 */
class Pages extends Base {

    /**
     * A service used to generate absolute paths to various resources used by
     * the framework or your application.
     *
     * @property SprayFire.FileSys.FireFileSys.Paths
     */
    protected $Paths;

    /**
     * Provides data used by the default SprayFire install home page; primarily
     * responsible for setting up the appropriate sidebar and providing sidebar
     * content.
     */
    public function index() {
        $this->setUp();

        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'index.php');
        $csprayGravatarHash = '0fd2816e78f6a04d5f8ce0aba1cb42e6';
        $dyanaGravatarHash = 'c1ca92616de3b725e808fb69a6bf94d2';

        $sidebarContent = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'sidebar.php');
        $sidebarData = \compact(
            'csprayGravatarHash',
            'dyanaGravatarHash'
        );

        $cleanData = array(
            'sidebarContent' => $sidebarContent
        );

        $dirtyData = array(
            'sidebarData' => $sidebarData,
        );

        $this->giveCleanData($cleanData);
        $this->giveDirtyData($dirtyData);
    }

    /**
     * Provides some basic data about the request, nothing too detailed and just
     * dumps that information out.
     */
    public function debug() {
        $this->setUp();

        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'debug-content.php');

        $serverData = \print_r($_SERVER, true);
        $sessionData = \print_r($_SESSION, true);
        $postData = \print_r($_POST, true);
        $getData = \print_r($_GET, true);
        $controller = \get_class();
        $action = $this->service('RoutedRequest')->getAction();
        $parameters = \print_r(\func_get_args(), true);

        $cleanData = \compact(
                    'controller'
                );
        $dirtyData = \compact(
                    'getData',
                    'postData',
                    'sessionData',
                    'serverData',
                    'action',
                    'parameters'
                );
        $this->giveCleanData($cleanData);
        $this->giveDirtyData($dirtyData);
    }

    /**
     * Sets up the controller with some generic functionality used by all actions.
     */
    protected function setUp() {
        $this->Paths = $this->service('Paths');
        $this->layoutPath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
    }

}