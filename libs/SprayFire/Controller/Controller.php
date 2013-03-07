<?php

/**
 * Interface that serves as a connection between the Model and Responder
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Controller;

use \SprayFire\Object as SFObject,
    \SprayFire\Service as SFService,
    \SprayFire\Responder as SFResponder,
    \SprayFire\Mediator as SFMediator;

/**
 * Designed to serve as a data conduit to the chose SprayFire.Responder.Responder
 * providing the chosen Responder with the appropriate information needed to send
 * the correct resource to the user.
 *
 * @package SprayFire
 * @package Controller
 */
interface Controller extends SFObject, SFService\Consumer {

    /**
     * Provides the fully namespaced name of the class to use as the Responder
     * for this controller; can be a Java or PHP-style name.
     *
     * @return string
     */
    public function getResponderName();

    /**
     * Return an array of data provided by setResponderData
     *
     * @param string $context
     * @return array
     */
    public function getResponderData($context = SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT);

    /**
     * Provide a set of data to the SprayFire.Responder.Responder that should be
     * used during response processing.
     *
     * The $data should be in the format [$varName => $varValue]
     *
     * @param array $data
     * @param string $context
     * @return void
     */
    public function setMultipleResponderData(array $data, $context = SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT);

    /**
     * Provide data to the SprayFire.Responder.Responder that should be used
     * during response processing.
     *
     * @param string $name
     * @param mixed $value
     * @param string $context
     * @return void
     */
    public function setResponderData($name, $value, $context = SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT);

    /**
     * Return an implementation of \SprayFire\Responder\Template\Manager that tells
     * the SprayFire.Responder.Responder implementation what layout and content
     * templates to use for the given request.
     *
     * @return \SprayFire\Responder\Template\Manager
     */
    public function getTemplateManager();

    /**
     * This method is invoked during dispatching of a request before the requested
     * controller action is invoked.
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function beforeAction(SFMediator\Event $Event);

    /**
     * This method is invoked during dispatching of a request after the requested
     * controller action is invoked.
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return void
     */
    public function afterAction(SFMediator\Event $Event);

}
