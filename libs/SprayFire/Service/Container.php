<?php

/**
 * Interface for classes acting as a Service container.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Service;

use \SprayFire\Object as Object;

/**
 * Service containers should be responsible for creating and storing services
 * used by SprayFire or, possibly, a SprayFire driven app.
 */
interface Container extends Object {

    /**
     * @param string $serviceName
     * @return object
     */
    public function getService($serviceName);

    /**
     * @param string $serviceName
     * @param callable|null $callableParameters
     */
    public function addService($serviceName, $callableParameters = null);

    /**
     * @param string $serviceName
     * @return bool
     */
    public function doesServiceExist($serviceName);

}