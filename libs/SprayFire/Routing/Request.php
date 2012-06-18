<?php

/**
 * An interface to provide information about the page/resource requested
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing;

interface Request {

    /**
     * The URI that was requested to generate this request
     *
     * @return string
     */
    public function getUri();

    /**
     * Return the controller portion of the URI requested
     *
     * @return string
     */
    public function getController();

    /**
     * Return the action portion of the URI requested
     *
     * @return string
     */
    public function getAction();

    /**
     * Return the parameters, including any marked, from the requested URI
     *
     * @return array
     */
    public function getParameters();

}