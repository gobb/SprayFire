<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller;

use \SprayFire\Controller\Base as BaseController;

class Pages extends BaseController {

    public function index() {
        $this->templatePath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'debug-content.php');
        $this->layoutPath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');

        $message = 'The requested resource could not be found.';
        $styleCss = $this->service('Paths')->getUrlPath('css', 'sprayfire.style.css');
        $sprayFireLogo = $this->service('Paths')->getUrlPath('images', 'sprayfire-logo-bar-75.png');
        $serverData = \print_r($_SERVER, true);
        $sessionData = \print_r($_SESSION, true);
        $postData = \print_r($_POST, true);
        $getData = \print_r($_GET, true);
        $controller = \get_class();
        $parameters = \print_r(\func_get_args(), true);

        $cleanData = \compact(
                    'styleCss',
                    'sprayFireLogo',
                    'message',
                    'controller',
                    'parameters'
                );
        $dirtyData = \compact(
                    'getData',
                    'postData',
                    'sessionData',
                    'serverData'
                );
        $this->giveCleanData($cleanData);
        $this->giveDirtyData($dirtyData);
    }

}