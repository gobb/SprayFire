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

    protected $Paths;

    public function setUp() {
        $this->Paths = $this->service('Paths');
        $this->layoutPath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'about.php');
    }

    public function sprayFire() {
        $this->setUp();
        $messages = array(
            'PHP 5.3 framework',
            'Developed by Charles Sprayberry',
            'Graphic Design by Dyana Stewart'
        );

        $css = array(
            $this->Paths->getUrlPath('css', 'sprayfire.style.css'),
            $this->Paths->getUrlPath('css', 'font-awesome.css'),
            $this->Paths->getUrlPath('css', 'bootstrap.min.css')
        );

        $this->giveCleanData(array(
            'css' => $css,
            'sprayFireLogo' => $this->Paths->getUrlPath('images', 'sprayfire-logo-bar-75.png'),
            'messages' => $messages
        ));
    }

}