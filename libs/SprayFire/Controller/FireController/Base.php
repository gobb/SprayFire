<?php

/**
 * Abstract implementation of SprayFire.Controller.Controller that allows for easy
 * sharing of generic functionality that it would be reasonable all implementations
 * to use.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller\Controller as Controller,
    \SprayFire\Service\FireService\Consumer as ServiceConsumer;

/**
 * Application controllers are expected to take advantage of the very basic functionality
 * provided by this object.
 *
 * The contract for SprayFire.Controller.Controller and SprayFire.Service.Consumer
 * is satisfied by this object and can be extended in such a way that overwriting
 * properties or altering properties at runtime, either at construction, as an event
 * or during action invocation alters the behavior of the implementation.
 *
 * If you overwrite the methods in this class please ensure that you return the
 * appropriate types as defined in the documentation of the interfaces SprayFire.Controller.Controller
 * and SprayFire.Service.Consumer.
 */
abstract class Base extends ServiceConsumer implements Controller {

    /**
     * The complete, absolute path to the layout used for this controller
     *
     * @property string
     */
    protected $layoutPath = '';

    /**
     * The complete, absolute path to the template used for this controller
     *
     * @property string
     */
    protected $templatePath = '';

    /**
     * The PHP or Java style namespaced class to use as the SprayFire.Responder.Responder
     * implementation for this controller.
     *
     * @property string
     */
    protected $responderName = 'SprayFire.Responder.FireResponder.Html';

    /**
     * An array of data that would need to be sanitized by the SprayFire.Responder.Responder
     * before the response is sent to the user.
     *
     * @property array
     */
    protected $dirtyData = array();

    /**
     * An array of data that does not necessarily need to be sanitized by the
     * SprayFire.Responder.Responder before the response is sent to the user.
     *
     * @property array
     */
    protected $cleanData = array();

    /**
     * Array of services provided to this SprayFire.Controller.Controller during
     * creation by the Controller Factory as defined by Base::$services
     *
     * @property array
     */
    protected $attachedServices = array();

    /**
     * Array of services that is provided by default to all implementations extending
     * this class.
     *
     * If you extend this class and overwrite this property the default services
     * will not be properly added and will not be made available to you.
     *
     * @property array
     */
    protected $services = array(
        'Paths' => 'SprayFire.FileSys.FireFileSys.Paths',
        'Request' => 'SprayFire.Http.FireHttp.Request',
        'RoutedRequest' => 'SprayFire.Http.Routing.FireRouting.RoutedRequest',
        'Logging' => 'SprayFire.Logging.FireLogging.LogDelegator'
    );

    /**
     * Java or PHP style namespaced class name.
     *
     * @return string
     */
    public function getResponderName() {
        return $this->responderName;
    }

    /**
     * The complete, absolute path to the layout for this controller
     *
     * @return string
     */
    public function getLayoutPath() {
        return $this->layoutPath;
    }

    /**
     * The complete, absolute path to the template for this controller
     *
     * @return string
     */
    public function getTemplatePath() {
        return $this->templatePath;
    }

    /**
     * Retrieve data that does not need to be escaped by the SprayFire.Responder.Responder.
     *
     * @return array
     */
    public function getCleanData() {
        return $this->cleanData;
    }

    /**
     * Retrieve data that should be escaped by the SprayFire.Responder.Responder.
     *
     * @return array
     */
    public function getDirtyData() {
        return $this->dirtyData;
    }

    /**
     * Provide data to the SprayFire.Responder.Responder that does NOT need to
     * be sanitized.
     *
     * @param array $data
     * @return void
     */
    public function giveCleanData(array $data) {
        $this->cleanData = \array_merge($this->cleanData, $data);
    }

    /**
     * Provide data to the SprayFire.Responder.Responder that DOES need to be
     * sanitized.
     *
     * @param array $data
     * @return void
     */
    public function giveDirtyData(array $data) {
        $this->dirtyData = \array_merge($this->dirtyData, $data);
    }

}