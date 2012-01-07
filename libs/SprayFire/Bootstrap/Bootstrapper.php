<?php

/**
 * @file
 * @brief Holds an interface for implementing objects that are responsible for framework
 * or app bootstrap functions.
 */

namespace SprayFire\Bootstrap;

/**
 * @brief An interface implemented by all framework and app bootstrapping objects.
 */
interface Bootstrapper {

    /**
     * @brief A method that should do whatever bootstrapping features are needed
     * for that particular bootstrap.
     */
    public function runBootstrap();

}