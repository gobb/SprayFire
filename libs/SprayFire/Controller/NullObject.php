<?php

/**
 * A default controller returned from the controller factory if the original controller
 * requested could not be located, ie a 404 response.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller;

use \SprayFire\Controller\Base as BaseController;

class NullObject extends BaseController {

    /**
     * Here to ensure that this object can invoke any action called upon it so that
     * if this is returned from the controller factory we don't have to change the
     * action used.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments) {
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

        $data = \compact(
                    'message',
                    'styleCss',
                    'sprayFireLogo',
                    'serverData',
                    'sessionData',
                    'postData',
                    'getData',
                    'controller'
                );
        $this->giveDirtyData($data);
    }

}