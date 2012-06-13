<?php

/**
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing;

interface Request {

    /**
     * @brief Return the Uri instance that was created to determine this request
     *
     * @return SprayFire.Routing.Uri
     */
    public function getUri();

    /**
     * @brief Return an array of strings that represent the top-level namespace
     * for the apps that should be
     *
     * @return array<string>
     */
    public function getApps();

    /**
     * @brief Return the name of the controller to instantiate for the request
     *
     * @return string
     */
    public function getNormalizedController();

    /**
     * @brief Return the method that should be invoked on the instantiated controller.
     *
     * @return string
     */
    public function getNormalizedAction();

}