## SprayFire\Plugin

**Dependencies:**

- SprayFire\Mediator\Callback
- SprayFire\Object

This module manages and takes care of ensuring plugins can be properly and easily integrated into the normal runtime operation of SprayFire. A plugin can be any third party library that (a) is *not* under the  `SprayFire` namespace and (b) is not a third party library dependend on by modules under the `SprayFire` namespace. So, as of v0.2.0 that means anything that isn't `SprayFire`, `ClassLoader`, or `Zend\OutputEscaper` is considered a plugin. Your app, third party libraries your app uses and anything else that needs to hook into SprayFire's runtime operations is a plugin.

### SprayFire\Plugin\Manager

This is the interface that takes care of the bulk of the work and it, well, manages plugins. It allows you to register something SprayFire calls 'plugin signatures', more on that below, to ensure your plugin gets plugged in. When a plugin is registered it should be setup for autoloading and the callbacks for that plugin should be reigstered against the framework's Mediator.

### SprayFire\Plugin\PluginSignature

This is how you tell the `Plugin\Manager` the information about your plugin; things like the name, directory to load classes from and what callbacks should be added to the Mediator. This is really a simple, dumb object that only passes along data about the plugin to the Manager.

## SprayFire\Plugin Events

To facilitate the bootstrapping of plugins and allow a clear, concise method for getting plugins started up the module will be adding two new events to the normal processing of the implemented SprayFire modules. The first event will be the `plugin_manager.app_load` event that will be triggered after the request is routed and we have determined the app to load but before any dispatching has taken place. The second event will be `plugin_manager.dispatch_started` that will be triggered after the app load event has taken place but before the dispatcher has done any processing.

The first event will be primarily used to get your app loaded. By default the SprayFire init file will create a PluginSignature for your app based off the normal conventions we have for retrieving app name and directory. The PluginSignature created by SprayFire for your app will only have 1 event callback, the `plugin_manager.app_load` event. This callback will initialize your application by looking for an expected app bootstrap. The `plugin_manager.dispatch_started` is to be used by your app's bootstrap to register app-specific plugins, this event will be triggered almost directly after the app load event is triggered and is the best way to ensure your plugins get started up.
