<?php

/**
 * A file that returns an array of PluginSignature objects that will be pre-registered
 * after your app is loaded but before any request dispatching has taken place.
 *
 * @author Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */

use \SprayFire\Plugin\FirePlugin as FirePlugin;

/** @var \SprayFire\FileSys\PathGenerator $Paths */
/** @var \SprayFire\EnvironmentConfig $EnvironmentConfig */

$storage = [];

// Create FirePlugin\PluginSignature implementations and add them to the storage!

return $storage;
