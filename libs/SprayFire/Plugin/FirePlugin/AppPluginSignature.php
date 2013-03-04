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
     * @param \SprayFire\Plugin\FirePlugin\PluginInitializer $Initializer
     */
    public function __construct(
        SFFileSys\PathGenerator $Paths,
        SFRouting\RoutedRequest $RoutedRequest,
        PluginInitializer $Initializer
    ) {
        $name = $RoutedRequest->getAppNamespace();
        $dir = $Paths->getAppPath();
        $callback = $this->getCallback($Initializer, $name);

        parent::__construct($name, $dir, $callback);
    }

    /**
     * Returns a callback function that can be passed to FirePlugin\PluginSignature
     * that returns a list of Mediator\Callback objects that should be added to
     * Mediator.
     *
     * @param \SprayFire\Plugin\FirePlugin\PluginInitializer $Initializer
     * @param string $name
     * @return callable
     */
    protected function getCallback(PluginInitializer $Initializer, $name) {
        return function() use($Initializer, $name) {
            $eventName = \SprayFire\Events::APP_LOAD;
            $eventCallback = function() use($Initializer, $name) {
                $Initializer->initializePlugin($name);
            };
            return [new FireMediator\Callback($eventName, $eventCallback)];
        };
    }

}
