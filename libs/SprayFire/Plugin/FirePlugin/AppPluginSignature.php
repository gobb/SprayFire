<?php

/**
 * An implementation of \SprayFire\Plugin\FirePlugin\PluginSignature that abstracts
 * away the creation of the PluginSignature for apps.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Plugin\FirePlugin;

use \SprayFire\Plugin as SFPlugin,
    \SprayFire\FileSys as SFFileSys,
    \SprayFire\Http\Routing as SFRouting,
    \SprayFire\Mediator\FireMediator as FireMediator,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFire
 * @subpackage Plugin.FirePlugin
 */
class AppPluginSignature extends PluginSignature {

    /**
     * @param \SprayFire\FileSys\PathGenerator $Paths
     * @param \SprayFire\Http\Routing\RoutedRequest $RoutedRequest
     * @param boolean $autoInitialize
     */
    public function __construct(SFFileSys\PathGenerator $Paths, SFRouting\RoutedRequest $RoutedRequest, $autoInitialize = true) {
        $name = $RoutedRequest->getAppNamespace();
        $dir = $Paths->getAppPath();
        $callback = function() { return []; };

        parent::__construct($name, $dir, $callback, $autoInitialize);
    }

}
