<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Helpers\Controller;

class TestPages extends \SprayFire\Controller\Base {

    public function __construct() {
        $this->services = array(
            'Paths' => 'SprayFire.FileSys.Paths'
        );
    }

    public function indexYoDog() {
        $this->responderName = 'SprayFire.Responder.HtmlResponder';
        $this->layoutPath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'just-templatecontents-around-div.php');
        $this->templatePath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'just-sprayfire.php');
    }

}