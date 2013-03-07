<?php

/**
 * Abstract implementation of \SprayFire\Controller\Controller that allows for easy
 * sharing of generic functionality that would be reasonable all implementations
 * to use.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Controller as SFController,
    \SprayFire\Mediator as SFMediator,
    \SprayFire\Responder as SFResponder,
    \SprayFire\Service\FireService as FireService;

/**
 * Application controllers are expected to take advantage of the very basic functionality
 * provided by this object.
 *
 * The contract for \SprayFire\Controller\Controller and \SprayFire\Service\Consumer
 * is satisfied by this object and can be extended in such a way that overwriting
 * properties or altering properties at runtime, either at construction, as an event
 * or during action invocation alters the behavior of the implementation.
 *
 * If you overwrite the methods in this class please ensure that you return the
 * appropriate types as defined in the documentation of \SprayFire\Controller\Controller
 * and SprayFire.Service.Consumer.
 *
 * @package SprayFire
 * @subpackage Controller.FireController
 *
 * @property \SprayFire\FileSys\FireFileSys\Paths $Paths
 * @property \SprayFire\Http\FireHttp\Request $Request
 * @property \SprayFire\Http\Routing\FireRouting\RoutedRequest $RoutedRequest
 * @property \SprayFire\Responder\Template\FireTemplate\Manager $TemplateManager
 * @property \SprayFire\Logging\FireLogging\LogOverseer $Logging
 */
abstract class Base extends FireService\Consumer implements SFController\Controller {

    /**
     * The PHP or Java style namespaced class to use as the \SprayFire\Responder\Responder
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
    protected $responderData = [];

    /**
     * Services that are provided by default to all implementations extending
     * this class.
     *
     * If you extend this class and overwrite this property the default services
     * will not be properly added and will not be made available to you.
     *
     * @property array
     */
    protected $services = [
        'Paths' => 'SprayFire.FileSys.FireFileSys.Paths',
        'Request' => 'SprayFire.Http.FireHttp.Request',
        'RoutedRequest' => 'SprayFire.Http.Routing.FireRouting.RoutedRequest',
        'Logging' => 'SprayFire.Logging.FireLogging.LogOverseer',
        'TemplateManager' => 'SprayFire.Responder.Template.FireTemplate.Manager'
    ];

    /**
     * Holds the parameters that were parsed from the Request and stored in the
     * RoutedRequest object; serves as a helper property to easily retrieve named
     * properties.
     *
     * @property array
     */
    protected $parameters = [];

    /**
     * Ensures that the appropriate storage for each escaping context is provided.
     */
    public function __construct() {
        $this->responderData[SFResponder\OutputEscaper::CSS_CONTEXT] = [];
        $this->responderData[SFResponder\OutputEscaper::HTML_ATTRIBUTE_CONTEXT] = [];
        $this->responderData[SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT] = [];
        $this->responderData[SFResponder\OutputEscaper::JAVASCRIPT_CONTEXT] = [];
    }

    /**
     * Sets the parameters from the RoutedRequest into the $parameters property.
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function beforeAction(SFMediator\Event $Event) {
        $this->parameters = $this->RoutedRequest->getParameters();
    }

    /**
     * Does not perform any action, here to allow implementations not to have empty
     * afterAction methods in their implementations.
     *
     * Although at this point this method does not perform any action it is still
     * advised that you call parent::afterAction() in any implementation that
     * overrides this for forward compatibility purposes.
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     *
     * @codeCoverageIgnore
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
     * Provide a set of data to the responder, should be in the format [$varName => $varValue]
     *
     * @param array $data
     * @param string $context
     * @return void
     */
    public function setMultipleResponderData(array $data, $context = SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT) {
        foreach ($data as $name => $value) {
            $this->setResponderData($name, $value, $context);
        }
    }

    /**
     * Provides a single data value to the set of data used by the responder
     *
     * @param string $name
     * @param mixed $value
     * @param string $context
     * @return void
     */
    public function setResponderData($name, $value, $context = SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT) {
        $this->responderData[$context][(string) $name] = $value;
    }

    /**
     * Provides a set of data that should be made available to the responder
     *
     * @param string $context
     * @return array
     */
    public function getResponderData($context = SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT) {
        return $this->responderData[$context];
    }

    /**
     * This is a service property that, by default, holds the
     *
     * @return \SprayFire\Responder\Template\Manager
     */
    public function getTemplateManager() {
        return $this->TemplateManager;
    }

}
