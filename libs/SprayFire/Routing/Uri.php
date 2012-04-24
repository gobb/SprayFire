<?php

/**
 * @file
 * @brief Holds an interface that is used to represent a URI requested through
 * the browser.
 */

namespace SprayFire\Routing;

/**
 * @brief All values returned from this interface should be URL decoded
 */
interface Uri {

    /**
     * @brief A value returned from getControllerFragment() if there was no controller
     * sent in the request
     */
    const NO_CONTROLLER_REQUESTED = 'NC';

    /**
     * @brief A value returned from getActionFragment() if there was no action sent in
     * the request
     */
    const NO_ACTION_REQUESTED = 'NA';

    /**
     * @brief A value used to separate parameters into key/value pairs or to mark
     * a section as a parameter.
     */
    const PARAMETER_SEPARATOR = ':';

    /**
     * @brief Should return the unaltered URI as it was sent for the request
     *
     * @return string
     */
    public function getRequestedUri();

    /**
     * @brief Returns the unaltered first section of the request after the domain
     * name and any sub directories that are required, if no controller was set
     * then Uri::NO_CONTROLLER_REQUESTED will be returned.
     *
     * @return string
     */
    public function getControllerFragment();

    /**
     * @brief Returns thge unaltered second section of the request after the
     * domain name and any sub directories that required, if no action was set
     * then Uri::NO_ACTION_REQUESTED will be returned.
     *
     * @return string
     */
    public function getActionFragment();

    /**
     * @brief Returns the remainder of any sections aftr the domain and any sub
     * directories that are required and after the controller or action.
     *
     * @details
     * Alternatively, parameters should allow some kind of separation and we should
     * capture both sides of the separator as a key/value pair.  This separator
     * can be used at any point but immediately makes all sections after that
     * parameter into parameters themselves.  So, if you had a URI with 4 sections
     * and the first was a parameter marked with the separate
     */
    public function getParameters();

}