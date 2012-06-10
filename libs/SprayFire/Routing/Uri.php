<?php

/**
 * Used to represent a URI requested through the browser.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing;

/**
 * Values returned from this interface should be URL decoded
 */
interface Uri {

    /**
     * Value returned from getControllerFragment() if there was no controller
     * sent in the request
     */
    const NO_CONTROLLER_REQUESTED = 'NC';

    /**
     * Value returned from getActionFragment() if there was no action sent in
     * the request
     */
    const NO_ACTION_REQUESTED = 'NA';

    /**
     * Value used to separate parameters into key/value pairs or to mark
     * a section as a parameter.
     */
    const PARAMETER_SEPARATOR = ':';

    /**
     * Should return the unaltered URI as it was sent for the request
     *
     * @return string
     */
    public function getRequestedUri();

    /**
     * Returns the unaltered first section of the request after the domain name
     * and any sub directories are removed, if no controller was set then
     * Uri::NO_CONTROLLER_REQUESTED will be returned.
     *
     * @return string
     */
    public function getControllerFragment();

    /**
     * Returns the unaltered second section of the request after the domain name
     * and any sub directories are removed, if no action was set then
     * Uri::NO_ACTION_REQUESTED will be returned.
     *
     * @return string
     */
    public function getActionFragment();

    /**
     * Returns the remainder of any sections aftr the domain name and any sub
     * directories are removed and after the controller and action.
     *
     * Alternatively, parameters should allow some kind of separation and we should
     * capture both sides of the separator as a key/value pair.  This separator
     * can be used at any point but immediately makes all sections after that
     * parameter into parameters themselves.  So, if you had a URI with 4 sections
     * and the first was a parameter marked with the separator all sections are
     * now considered parameters, whether they are marked or not.
     *
     * @return array
     */
    public function getParameters();

}