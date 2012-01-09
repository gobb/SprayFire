<?php

/**
 * @file
 * @brief The interface necessary to turn a URI string into the appropriate fragments needed
 * to process the request.
 */

namespace SprayFire\Request;

/**
 * @brief Implementations should convert a string into appropriate fragments
 * for the requested contoller, action and parameters.
 *
 * @details
 * The implementation of this interface should take into account that the Uri
 * passed may contain a leading `/` and the name of the installing directory
 * if the framework is installed in a subdirectory of the server root.
 *
 * The following pattern describes the general format URIs should be interpreted:
 *
 * <code>[install_dir]/controller/action/param1/param2/paramN</code>
 *
 * If no value is returned from these then the default values should be returned.
 * For this interface the default values are:
 *
 * Default controller = SprayFire.Request.Uri::DEFAULT_CONTROLLER
 * Default action = SprayFire.Request.Uri::DEFAULT_ACTION
 * Default parameters = array()
 */
interface Uri {

    /**
     * Returned if the controller fragment of the URI is not set.
     *
     * @property DEFAULT_CONTROLLER
     */
    const DEFAULT_CONTROLLER = '%default_controller%';

    /**
     * Returned if the default action should be used.
     *
     * @property DEFAULT_ACTION
     */
    const DEFAULT_ACTION = '%default_action%';

    /**
     * @return string The original unaltered URI
     */
    public function getOriginalUri();

    /**
     * @return string The URI controller fragment or DEFAULT_CONTROLLER if no controller was given
     */
    public function getController();

    /**
     * @return string The URI action fragment or DEFAULT_ACTION if no action was given
     */
    public function getAction();

    /**
     * @return array An array of parameter data fragments or an empty array if none were given
     */
    public function getParameters();

    /**
     * @details
     * This is here because the Uri implementation needs to know about the root
     * install directory to remove any erroneous fragments.
     *
     * @return String representing directory the app, libs, web dir is stored in
     */
    public function getRootDirectory();

}