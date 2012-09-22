<?php

/**
 * An interface implemented by all SprayFire and app controllers; allows for the
 * retrieval of information about the services the controller needs and provides
 * a means to give data to the Responder
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller;

use \SprayFire\Object as Object,
    \SprayFire\Service\Consumer as ServiceConsumer;

/**
 * @TODO
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
 */
interface Controller extends Object, ServiceConsumer {

    /**
     * Provides the fully namespaced name of the class to use as the Responder
     * for this controller; can be a Java or PHP-style name.
     *
     * @return string
     */
    public function getResponderName();

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

    /**
     * Should provide a means to give a Responder data that is to be considered
     * unsanitized.
     *
     * @param array $data
     * @return mixed
     */
    public function giveDirtyData(array $data);

    /**
     * Provide a means to give a Responder data that is to be considered safe
     * and should not go through sanitization.
     *
     * @param array $data
     * @return mixed
     */
    public function giveCleanData(array $data);

    /**
     * Should return an array of data given to the Responder that is considered
     * dirty and should be sanitized in some way.
     *
     * @return array
     */
    public function getDirtyData();

    /**
     * Should return an array of data given to the Responder that is considered
     * clean and should not be sanitized.
     *
     * @return array
     */
    public function getCleanData();

}