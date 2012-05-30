<?php

/**
 * @file
 * @brief An interface for converting a Uri instance into a SprayFire.Routing.Request
 * instance
 */

namespace SprayFire\Routing;

/**
 * @brief
 */
interface Router {

    /**
     * @param $Uri SprayFire.Routing.Uri
     * @return SprayFire.Routing.Request
     */
    public function getRequest(\SprayFire\Routing\Uri $Uri);

}