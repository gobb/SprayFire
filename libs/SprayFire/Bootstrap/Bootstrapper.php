<?php

/**
 * An interface for implementing objects that are responsible for framework or app
 * bootstrap functions.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Bootstrap;

/**
 * This interface should be implemented by all framework and app bootstrapping
 * objects.
 */
interface Bootstrapper {

    /**
     * A method that should do whatever bootstrapping features are needed for that
     * particular bootstrap.
     *
     * @return mixed Values returned from bootstraps should be dependent on the type
     * of bootstrap being ran
     */
    public function runBootstrap();

}
