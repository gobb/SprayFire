<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Helpers\Controller;

class DispatchNullObject extends \SprayFire\Controller\FireController\Base {

    public function __construct() {
        $this->services = array(
            'Paths' => 'SprayFire.FileSys.FireFileSys.Paths'
        );
        $this->responderName = 'SprayFire.Responder.HtmlResponder';
    }

    public function __call($name, $arguments) {
        $this->layoutPath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'just-templatecontents-around-div.php');
        $this->templatePath = $this->service('Paths')->getLibsPath('SprayFire', 'Responder', 'html', 'no-action-message.php');
    }

}