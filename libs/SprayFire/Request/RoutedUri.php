<?php

/**
 * @file
 * @brief Holds the interface for a Uri object that has been properly routed to a
 * mapped controller and action.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Request;

/**
 * @brief An extension of the SprayFire.Request.Uri interface to allow
 * for the setting of the original URI string used to map the RoutedUri object
 * created and a means to retrieve the mapped URI string.
 */
interface RoutedUri extends \SprayFire\Request\Uri {

    /**
     * @return The URI string that was passed in this objects contructor
     */
    public function getRoutedUri();

    /**
     * @brief Provides a means to set the original URI string that this RoutedUri
     * was mapped from; this string should be returned by
     * SprayFire.Request.Uri::getOriginalUri().
     *
     * @param $uri string
     */
    public function setOriginalUri($uri);

}