<?php

/**
 * An interface implemented by all SprayFire and app controllers; allows for the
 * retrieval of information about the services the controller needs and provides
 * a means to give data to the Responder
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Controller;

interface Controller {

    /**
     * Provides the fully namespaced name of the class to use as the Responder
     * for this controller; can be a Java or PHP-style name.
     *
     * @return string
     */
    public function getResponderName();

    /**
     * Should provide a means to give a Responder data that is to be considered
     * unsanitized.
     *
     * @param $data array
     */
    public function giveDirtyData(array $data);

    /**
     * Provide a means to give a Responder data that is to be considered safe
     * and should not go through sanitization.
     *
     * @param $data array
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