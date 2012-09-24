<?php

/**
 * Implementation of SprayFire.Controller.Controller provided in the default SprayFire
 * install
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller as SFController;


/**
 * This implementation is intended to provide the user some more detailed info on
 * SprayFire as well as its creator and contributors.
 *
 * This controller is not required to stick around, but please remove the appropriate
 * route, '/about/', if you decide to remove this from your SprayFire install.
 *
 * @package SprayFire
 * @package Controller.FireController
 */
class About extends Base implements SFController\Controller {

    /**
     * A default service provided by SprayFire.Controller.FireController.Base.
     *
     * This property is here as a convenience to not need to call $this->service()
     * everytime we need to use the service.
     *
     * @property SprayFire.FileSys.PathGenerator
     */
    protected $Paths;

    /**
     * Takes care of some generic actions that may be used by multiple actions
     *
     * @return void
     */
    protected function setUp() {
        $this->Paths = $this->service('Paths');
        $this->layoutPath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'layout', 'default.php');
        $this->templatePath = $this->Paths->getLibsPath('SprayFire', 'Responder', 'html', 'about.php');
    }

    /**
     * Prepares data needed to let the user know more about the framework.
     *
     * @return void
     */
    public function sprayFire() {
        $this->setUp();
        $messages = array(
            'PHP 5.3 framework',
            'Developed by Charles Sprayberry',
            'Graphic Design by Dyana Stewart'
        );
        $this->giveCleanData(array(
            'messages' => $messages
        ));
    }

}