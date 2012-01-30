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
     *
     * @details
     * It should be noted that if there is a problem running the bootstrap then a
     * SprayFire.Exception.BootstrapFailedException should be thrown.  If you throw
     * a different type of exception from this method then the calling code may
     * not properly catch it.
     *
     * @return A container storing objects created by a given bootstrap
     * @throws SprayFire.Exception.BootstrapFailedException
     */
    public function runBootstrap();

}
