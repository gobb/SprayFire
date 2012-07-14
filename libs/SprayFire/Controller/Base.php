<?php

/**
 * The base framework controller, by default inherited by framework provided controllers
 * and is a prime choice for the parent controller in your app controllers.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Controller;

use \SprayFire\Controller\Controller as Controller,
    \SprayFire\Service\Firebox\Consumer as ServiceConsumer;

abstract class Base extends ServiceConsumer implements Controller {

    /**
     * @property string
     */
    protected $layoutPath = '';

    /**
     * @property string
     */
    protected $templatePath = '';

    /**
     * @property string
     */
    protected $responderName = '';

    /**
     * An array of data that would need to be sanitized by the Responder before
     * the response is sent to the user.
     *
     * @property array
     */
    protected $dirtyData = array();

    /**
     * An array of data that does not necessarily need to be sanitized by the
     * Responder before the response is sent to the user.
     *
     * @property array
     */
    protected $cleanData = array();

    /**
     * Array of objects added to this Controller during the controller setup
     * process.
     *
     * @property array
     */
    protected $attachedServices = array();

    public function __construct() {
        $this->services = array(
            'Paths' => 'SprayFire.FileSys.Paths',
            'Request' => 'SprayFire.Http.StandardRequest',
            'Logging' => 'SprayFire.Logging.Logifier.LogDelegator'
        );
        $this->responderName = 'SprayFire.Responder.HtmlResponder';
    }

    /**
     * @return string
     */
    public function getResponderName() {
        return $this->responderName;
    }

    /**
     * @return string
     */
    public function getLayoutPath() {
        return $this->layoutPath;
    }

    /**
     * @return string
     */
    public function getTemplatePath() {
        return $this->templatePath;
    }

    /**
     * @return array
     */
    public function getCleanData() {
        return $this->cleanData;
    }

    /**
     * @return array
     */
    public function getDirtyData() {
        return $this->dirtyData;
    }

    /**
     * @param array $data
     * @return void
     */
    public function giveCleanData(array $data) {
        $this->cleanData = \array_merge($this->cleanData, $data);
    }

    /**
     * @param array $data
     * @return void
     */
    public function giveDirtyData(array $data) {
        $this->dirtyData = \array_merge($this->dirtyData, $data);
    }

}