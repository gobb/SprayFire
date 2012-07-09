<?php

/**
 * An interface implemented by all SprayFire and app controllers; allows for the
 * retrieval of information about the services the controller needs and provides
 * a means to give data to the Responder
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Controller;

use \SprayFire\Service\Consumer as ServiceConsumer;

interface Controller extends ServiceConsumer {

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