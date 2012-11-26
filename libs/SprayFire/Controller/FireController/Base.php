<?php

/**
 * Abstract implementation of SprayFire.Controller.Controller that allows for easy
 * sharing of generic functionality that would be reasonable all implementations
 * to use.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller as SFController,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\Service\FireService as FireService;

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
 * appropriate types as defined in the documentation of SprayFire.Controller.Controller
 * and SprayFire.Service.Consumer.
 *
 * @package SprayFire
 * @subpackage Controller.FireController
 */
abstract class Base extends FireService\Consumer implements SFController\Controller {

    /**
     * The PHP or Java style namespaced class to use as the SprayFire.Responder.Responder
     * implementation for this controller.
     *
     * @property string
     */
    protected $responderName = 'SprayFire.Responder.FireResponder.Html';

    /**
     * Stores the data that will be made available to the SprayFire.Responder.Responder
     *
     * @property array
     */
    protected $responderData = array();

    /**
     * Array of services provided to this SprayFire.Controller.Controller during
     * creation by the Controller Factory as defined by Base::$services
     *
     * @property array
     */
    protected $attachedServices = array();

    /**
     * @property SprayFire.FileSys.PathGenerator
     */
    protected $Paths;

    /**
     * @property SprayFire.Http.Request
     */
    protected $Request;

    /**
     * @property SprayFire.Http.Routing.RoutedRequest
     */
    protected $RoutedRequest;

    /**
     * @property SprayFire.Logging.LogOverseer
     */
    protected $Logging;

    /**
     * @property SprayFire.Responder.Template.Manager
     */
    protected $TemplateManager;

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
        'Logging' => 'SprayFire.Logging.FireLogging.LogOverseer',
        'TemplateManager' => 'SprayFire.Responder.FireResponder.FireTemplate.Manager'
    );

    /**
     * @param SprayFire.Mediator.Event $Event
     * @return void
     */
    public function beforeAction(SFMediator\Event $Event) {
        $properties = \get_object_vars($this);
        foreach ($this->services as $serviceKey => $serviceName) {
            if (\array_key_exists($serviceKey, $properties)) {
                $this->$serviceKey = $this->service($serviceKey);
            }
        }
    }

    /**
     * @param SprayFire.Mediator.Event $Event
     * @return void
     */
    public function afterAction(SFMediator\Event $Event) {

    }

    /**
     * Java or PHP style namespaced class name.
     *
     * @return string
     */
    public function getResponderName() {
        return $this->responderName;
    }

    /**
     * Provide a set of data to the responder, should be in the format
     * [$varName => $varValue]
     *
     * @param array $data
     */
    public function setMultipleResponderData(array $data) {
        foreach ($data as $name => $value) {
            $this->setResponderData($name, $value);
        }
    }

    /**
     * Provides a single data value to the set of data used by the responder
     *
     * @param string $name
     * @param mixed $value
     */
    public function setResponderData($name, $value) {
        $this->responderData[(string) $name] = $value;
    }

    /**
     * Provides a set of data that should be made available to the responder
     *
     * @return array
     */
    public function getResponderData() {
         return $this->responderData;
    }

    /**
     * @return SprayFire.Responder.Template.Manager
     */
    public function getTemplateManager() {
        return $this->TemplateManager;
    }

}