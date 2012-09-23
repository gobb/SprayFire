<?php

/**
 * An implementation of SprayFire.Controller.Controller that should perform no
 * operations.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller;

use \SprayFire\Controller\Controller as Controller,
    \SprayFire\CoreObject as CoreObject;

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
 * By default this object will only return values for
 *
 * - getResponderName() // SprayFire.Responder.FireResponder.Html
 * - getLayoutPath()    // install_dir/Responder/html/default.php
 * - getTemplatePath()  // install_dir/Responder/html/blank.php
 */
class NullObject extends CoreObject implements Controller {

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
     * No operation; returns empty array
     *
     * @return array
     */
    public function getCleanData() {
        return array();
    }

    /**
     * No operation; returns empty array
     *
     * @return array
     */
    public function getDirtyData() {
        return array();
    }

    /**
     * Returns a layout path to be used by a SprayFire.Responder.FireResponder.Html
     * object.
     *
     * @return string
     */
    public function getLayoutPath() {
        return \dirname(__DIR__) . '/Responder/html/layout/default.php';
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
     * Returns a template path to be used by a SprayFire.Responder.FireResponder.Html
     * object.
     *
     * @return string
     */
    public function getTemplatePath() {
        return \dirname(__DIR__) . '/Responder/html/blank.php';
    }

    /**
     * No operation; no data will be provided to layout or template
     *
     * @param array $data
     */
    public function giveCleanData(array $data) {

    }

    /**
     * No operation; no data will be provided to layout or template
     *
     * @param array $data
     */
    public function giveDirtyData(array $data) {

    }

    /**
     * No operation; returns an empty array
     *
     * @return array
     */
    public function getRequestedServices() {
        return array();
    }

    /**
     * No operation; no service is provided
     *
     * @param string $key
     * @param object $Service
     */
    public function giveService($key, $Service) {

    }

}