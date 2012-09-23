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

use \SprayFire\Controller\FireController\Base as BaseController;

class Pages extends BaseController {

    /**
     * @property SprayFire.FileSys.FireFileSys.Paths
     */
    protected $Paths;

    public function index() {
        $this->setUp();

        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'index.php');
        $this->layoutPath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
        $css = array(
            $this->Paths->getUrlPath('css', 'sprayfire.style.css'),
            $this->Paths->getUrlPath('css', 'font-awesome.css'),
            $this->Paths->getUrlPath('css', 'bootstrap.min.css')
        );
        $twitterBootstrapJs = $this->Paths->getUrlPath('js', 'bootstrap.min.js');
        $sprayFireLogo = $this->Paths->getUrlPath('images', 'sprayfire-logo-bar-75.png');
        $csprayGravatarHash = '0fd2816e78f6a04d5f8ce0aba1cb42e6';
        $dyanaGravatarHash = 'c1ca92616de3b725e808fb69a6bf94d2';

        $sidebarContent = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'sidebar.php');
        $sidebarData = \compact(
            'csprayGravatarHash',
            'dyanaGravatarHash'
        );

        $cleanData = array(
            'css' => $css,
            'sprayFireLogo' => $sprayFireLogo,
            'twitterBootstrapJs' => $twitterBootstrapJs,
            'csprayGravatarHash' => $csprayGravatarHash,
            'dyanaGravatarHash' => $dyanaGravatarHash,
            'sidebarContent' => $sidebarContent,
            'message' => ''
        );

        $dirtyData = array(
            'sidebarData' => $sidebarData,
        );

        $this->giveCleanData($cleanData);
        $this->giveDirtyData($dirtyData);
    }

    public function debug() {
        $this->setUp();

        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'debug-content.php');
        $this->layoutPath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
        $styleCss = $this->Paths->getUrlPath('css', 'sprayfire.style.css');
        $fontAwesomeCss = $this->Paths->getUrlPath('css', 'font-awesome.css');
        $twitterBootstrapCss = $this->Paths->getUrlPath('css', 'bootstrap.min.css');
        $twitterBootstrapJs = $this->Paths->getUrlPath('js', 'bootstrap.min.js');

        $sprayFireLogo = $this->Paths->getUrlPath('images', 'sprayfire-logo-bar-75.png');
        $serverData = \print_r($_SERVER, true);
        $sessionData = \print_r($_SESSION, true);
        $postData = \print_r($_POST, true);
        $getData = \print_r($_GET, true);
        $controller = \get_class();
        $action = $this->service('RoutedRequest')->getAction();
        $parameters = \print_r(\func_get_args(), true);

        $cleanData = \compact(
                    'styleCss',
                    'fontAwesomeCss',
                    'twitterBootstrapCss',
                    'sprayFireLogo',
                    'message',
                    'controller',
                    'parameters'
                );
        $dirtyData = \compact(
                    'getData',
                    'postData',
                    'sessionData',
                    'serverData',
                    'action'
                );
        $this->giveCleanData($cleanData);
        $this->giveDirtyData($dirtyData);
    }

    protected function setUp() {
        $this->Paths = $this->service('Paths');
    }

}