<?php

/**
 * Interface for objects that require services from SprayFire.Service.Container.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Service;

use \SprayFire\Object as SFObject;

/**
 * Implementations of this object are expected to provide a mechanism for retrieving
 * a list of services needed from a Container and to provide storage for those
 * services if they are property stored by the container.
 *
 * @package SprayFire
 * @subpackage Service
 */
interface Consumer extends SFObject {

    /**
     * An associative array with $key => $nameOfService; the $key should be passed
     * to giveService() along with the created $Service.
     *
     * @return array
     */
    public function getRequestedServices();

    /**
     * Provide a service to the Consumer.
     *
     * @param string $key
     * @param object $Service
     */
    public function giveService($key, $Service);

}
