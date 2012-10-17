<?php

/**
 * Interface that serves as a connection between the Model and Responder
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller;

use \SprayFire\Object as SFObject,
    \SprayFire\Service as SFService;

/**
 * Designed to serve as a data conduit to the chose SprayFire.Responder.Responder
 * providing the chosen Responder with the appropriate information needed to send
 * the correct resource to the user.
 *
 * @todo
 * This interface should reevaluate how it is providing data to the SprayFire.Responder.Responder
 * and whether or not it should be providing a mechanism for giving clean or
 * dirty data.  Should we not assume that all data is dirty?  If you are providing
 * data in a way that requires data to not be escaped on output is this not a
 * security flaw in your application?
 *
 * In the context of providing data to a SprayFire.Responder.Responder the data
 * should always be escaped.  By allowing data, regardless of the source, to be
 * output without first being escaped you are opening a security flaw into your
 * application.  If it can be used it can be compromised at some level.
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
     * Provide data to the SprayFire.Responder.Responder that should be used
     * during response processing.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setResponderData($name, $value);

    /**
     * Return an array of data provided by setResponderData
     *
     * @return array
     */
    public function getResponderData();

    /**
     * Return the full path to the template file, including file extension
     *
     * @return string
     */
    public function getTemplatePath();

    /**
     * Return the full path to the layout for this template, include file extension
     *
     * @return string
     */
    public function getLayoutPath();

}