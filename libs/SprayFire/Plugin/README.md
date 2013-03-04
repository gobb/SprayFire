## SprayFire\Plugin

**Dependencies:**

- SprayFire\Mediator\Callback
- SprayFire\Object

This module manages and takes care of ensuring plugins can be properly and easily integrated into the normal runtime operation of SprayFire. A plugin can be any third party library that (a) is *not* under the  `SprayFire` namespace and (b) is not a third party library dependend on by modules under the `SprayFire` namespace. So, as of v0.2.0 that means anything that isn't `SprayFire`, `ClassLoader`, or `Zend\OutputEscaper` is considered a plugin. Your app, third party libraries your app uses and anything else that needs to hook into SprayFire's runtime operations is a plugin.

### SprayFire\Plugin\Manager

This is the interface that takes care of the bulk of the work and it, well, manages plugins. It allows you to register something SprayFire calls 'plugin signatures', more on that below, to ensure your plugin gets plugged in. When a plugin is registered it should be setup for autoloading and the callbacks for that plugin should be reigstered against the framework's Mediator.

### SprayFire\Plugin\PluginSignature

This is how you tell the `Plugin\Manager` the information about your plugin; things like the name, directory to load classes from, what callbacks should be added to the Mediator and whether the app should be ran through the `FirePlugin\PluginInitializer`. This is really a simple, dumb object that only passes along data about the plugin to the Manager.

## SprayFire\Plugin\FirePlugin

**Dependencies:**
- SprayFire\Bootstrap
- SprayFire\Dispatcher\Exception
- SprayFire\Plugin
- SprayFire\Service
- SprayFire\StdLib
- SprayFire\Mediator\FireMediator
- ClassLoader\Loader

> The dependency on `SprayFire\Dispatcher\Exception` is an artifact relating back to when the PluginInitializer was a part of that module. This will be removed eventually once it is determined the appropriate exception that should be used in place of the ones currently being utilized.

### SprayFire\Plugin\FirePlugin\PluginSignature

### SprayFire\Plugin\FirePlugin\PluginInitializer

### SprayFire\Plugin\FirePlugin\Manager
