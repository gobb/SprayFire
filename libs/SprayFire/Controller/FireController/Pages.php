<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller\FireController\Base as BaseController;

class Pages extends BaseController {

    /**
     * @property SprayFire.FileSys.FireFileSys.Paths
     */
    protected $Paths;

    public function setServices() {
        $this->Paths = $this->service('Paths');
    }

    public function index() {
        $this->setServices();

        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'index.php');
        $this->layoutPath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
        $css = array(
            $this->Paths->getUrlPath('css', 'sprayfire.style.css'),
            $this->Paths->getUrlPath('css', 'font-awesome.css'),
            $this->Paths->getUrlPath('css', 'bootstrap.min.css')
        );
        $twitterBootstrapJs = $this->Paths->getUrlPath('js', 'bootstrap.min.js');
        $csprayGravatarHash = \md5('cspray@gmail.com');
        $dyanaGravatarHash = \md5('dystewart249@gmail.com');
        $sidebarContent = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'sidebar.php');
        $sidebarData = \compact(
            'csprayGravatarHash',
            'dyanaGravatarHash'
        );
        $sprayFireLogo = $this->Paths->getUrlPath('images', 'sprayfire-logo-bar-75.png');

        $cleanData = array(
            'css' => $css,
            'sprayFireLogo' => $sprayFireLogo,
            'twitterBootstrapJs' => $twitterBootstrapJs,
            'csprayGravatarHash' => $csprayGravatarHash,
            'dayanaGravatarHash' => $dyanaGravatarHash,
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
        $this->setServices();

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

}