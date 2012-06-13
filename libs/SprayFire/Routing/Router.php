<?php

/**
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing;

interface Router {

    /**
     * @param $Uri SprayFire.Routing.Uri
     * @return SprayFire.Routing.Request
     */
    public function getRequest(\SprayFire\Routing\Uri $Uri);

}