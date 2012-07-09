<?php

/**
 * An interface for objects that use services from a SprayFire.Service.Container.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Service;

interface Consumer {

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