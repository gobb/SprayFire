<?php

/**
 * An implementation of \SprayFire\Controller\Controller that should perform no
 * operations.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Controller;

use \SprayFire\Controller as SFController,
    \SprayFire\Mediator,
    \SprayFire\Responder,
    \SprayFire\StdLib,
    \SprayFire\Responder\Template\FireTemplate;

/**
 * By default this object is returned from the SprayFire.Controller.FireController.Factory
 * implementation and can be used as a 404 object, controlling what is displayed
 * when the appropriate resource could not be found.
 *
 * This object does not allow the supporting of services and does not extend
 * SprayFire.Controller.FireController.Base by design.  This is intentionally
 * supposed to be as minimal as possible and to perform no operations for the
 * majority of calls.
 *
 * @package SprayFire
 * @subpackage Controller.Implementation
 *
 * @codeCoverageIgnore
 */
class NullObject extends StdLib\CoreObject implements SFController\Controller {

    /**
     * Returned from self::getTemplateManager to allow the controller to determine
     * what layout and content templates are provided.
     *
     * @property \SprayFire\Responder\Template\Manager
     */
    protected $TemplateManager;

    /**
     * Create a \SprayFire\Responder\FireResponder\FireTemplate\Manager instance
     * and set the appropriate layout template.
     */
    protected function setUpTemplateManager() {
        $name = 'layoutTemplate';
        $layoutFile = \dirname(__DIR__) . '/Responder/html/layout/default.php';
        $LayoutTemplate = new FireTemplate\FileTemplate($name, $layoutFile);
        $Manager = new FireTemplate\Manager();
        $Manager->setLayoutTemplate($LayoutTemplate);

        $this->TemplateManager = $Manager;
    }

    /**
     * Here to ensure that this object can invoke any action called upon it so that
     * if this is returned from the controller factory we don't have to change the
     * action used.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments) {

    }

    /**
     * Ensures that the TemplateManager object has been properly setup.
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function beforeAction(Mediator\Event $Event) {
        $this->setUpTemplateManager();
    }

    /**
     * No operation performed
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function afterAction(Mediator\Event $Event) {

    }

    /**
     * No operation performed
     *
     * @param array $data
     * @param string $context
     * @return void
     */
    public function setMultipleResponderData(array $data, $context = Responder\OutputEscaper::HTML_CONTENT_CONTEXT) {

    }

    /**
     * No operation performed
     *
     * @param string $name
     * @param mixed $value
     * @param string $context
     */
    public function setResponderData($name, $value, $context = Responder\OutputEscaper::HTML_CONTENT_CONTEXT) {

    }

    /**
     * No operation performed; will always return an empty array
     *
     * @param string $context
     * @return array
     */
    public function getResponderData($context = Responder\OutputEscaper::HTML_CONTENT_CONTEXT) {
        return [];
    }

    /**
     * Returns the default HTML responder provided by the framework
     *
     * @return string
     */
    public function getResponderName() {
        return 'SprayFire.Responder.FireResponder.Html';
    }

    /**
     * No operation; returns an empty array
     *
     * @return array
     */
    public function getRequestedServices() {
        return [];
    }

    /**
     * No operation; no service is provided
     *
     * @param string $key
     * @param object $Service
     */
    public function giveService($key, $Service) {

    }

    /**
     * No operation; no services are added
     *
     * @return void
     */
    public function beforeBuild() {

    }

    /**
     * @return \SprayFire\Responder\Template\Manager
     */
    public function getTemplateManager() {
        return $this->TemplateManager;
    }



}
