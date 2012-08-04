<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller\FireController\Base as BaseController;

class About extends BaseController implements \SprayFire\Controller\Controller {

    public function setUp() {
        $this->layoutPath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
        $this->templatePath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'about.php');
    }

    public function sprayFire() {
        $this->setUp();
        $messages = array(
            'PHP 5.3 framework',
            'Developed by Charles Sprayberry',
            'Graphic Design by Dyana Stewart'
        );

        $this->giveCleanData(array(
            'styleCss' => $this->service('Paths')->getUrlPath('css', 'sprayfire.style.css'),
            'sprayFireLogo' => $this->service('Paths')->getUrlPath('images', 'sprayfire-logo-bar-75.png'),
            'messages' => $messages
        ));
    }

}