<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Resource;

/**
 * @brief
 */
interface Container {

    public function getResource($resourceName);

    public function doesResourceExist($resourceName);

    public function addResource($resourceName, array $parameters = array());

    public function setResourceFactory($resourceType, $factoryName);

}