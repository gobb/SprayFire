## SprayFire\Plugin

**Dependencies:**

- SprayFire\Object

This module manages and takes care of ensuring plugins can be properly and easily integrated into the normal runtime operation of SprayFire. A plugin can be any third party library that (a) is *not* under the  `SprayFire` namespace and (b) is not a third party library dependend on by modules under the `SprayFire` namespace. So, as of v0.2.0 that means anything that isn't `SprayFire`, `ClassLoader`, or `Zend\OutputEscaper` is considered a plugin. Your app, third party libraries your app uses and anything else that needs to hook into SprayFire's runtime operations is a plugin.

### SprayFire\Plugin\Manager

This is the interface that takes care of the bulk of the work and it, well, manages plugins. It allows you to register something SprayFire calls 'plugin signatures', more on that below, to ensure your plugin gets plugged in. When a plugin is registered it should be setup for autoloading and, if appropriate for that plugin, a bootstrap should be ran. By convention this bootstrap is typically expected to be at `PluginName\\Bootstrap`.

### SprayFire\Plugin\PluginSignature

This is how you tell the `Plugin\Manager` the information about your plugin; things like the name, directory to load classes from, and whether the app should be ran through the `FirePlugin\PluginInitializer`. This is really a simple, dumb object that only passes along data about the plugin to the Manager.

## SprayFire\Plugin\FirePlugin

**Dependencies:**
- SprayFire\Bootstrap
- SprayFire\Plugin
- SprayFire\Service\Container
- SprayFire\StdLib\CoreObject
- ClassLoader\Loader

### SprayFire\Plugin\FirePlugin\PluginSignature

A very simple implementation of the `SprayFire\Plugin\PluginSignature` that allows the information needed to complete the requirements of the interface to the constructor of the new object.

```php
<?php

use \SprayFire\Plugin as SFPlugin,
    \SprayFire\Plugin\FirePlugin as FirePlugin;

// We're gonna create 3 PluginSignatures.
// SprayFireDemo and Doctrine will NOT be initialized while YourApp will
// This means we're expecting a \YourApp\Bootstrap to be created and invoked
// when the YourApp plugin is registered

// Plugin 1 = SprayFireDemo with src residing in /app/SprayFireDemo
// Plugin 2 = YourApp with src residing in /app/some_vendor/YourApp
// Plugin 3 = Doctrine with src residing in /libs/Doctrine

$SprayFireDemo = new FirePlugin\PluginSignature('SprayFireDemo', '/app', SFPlugin\PluginSignature::DO_NOT_INITIALIZE);
$YourApp = new FirePlugin\PluginSignature('YourApp', '/app', SFPlugin\PluginSignature::DO_INITIALIZE);
$Doctrine = new FirePlugin\PluginSignature('Doctrine', '/libs', SFPlugin\PluginSignature::DO_NOT_INITIALIZE);

?>
```

### SprayFire\Plugin\FirePlugin\PluginInitializer

A concrete class not backed by an interface that runs a plugin's bootstrap, if the `PluginSignature` has been configured to do so. Right now this implementation expects a `SprayFire\Bootstrap\Bootstrapper` implementation called `PluginName\Bootstrap` to be available. If either of these two things are not true an exception will be thrown. Additionally, this implementation will pass two parameters, a `SprayFire\Service\Container` and a `ClassLoader\Loader`, to the constructor of this bootstrap. This will allow your plugin to setup any additional autoloading and to add services to the Container if needed. SprayFire provides a `SprayFire\Bootstrap\FireBootstrap\Pluggable` implementation that will allow extending classes access to these two services as properties of the class.

This implementation is really anticipated to be used by the `FirePlugin\Manager` detailed below. We do not expose this object to the app by default and it works by passing the top level app namespace to `PluginInitializer::initializeApp()` if you do gain access to it.

### SprayFire\Plugin\FirePlugin\Manager

This implementation handles autoloading setup and plugin initialization when a plugin is registered. You can register a plugin one at a time or as a collection of plugins. You can also retrieve all the PluginSignature that have been registered for this Manager.

```php
<?php

// In the framework the best way to access this object is through the normal
// Service\Consumer mechanisms. Below $Manager is assumed to be a FirePlugin\Manager
// service retrieved through this module.

$Manager->getRegisteredPlugins(); // returns [] if no plugins registered

// Using the PluginSignature implementations from above

// The next three blocks of code are completely equivalent to each other in
// final goal of getting all 3 PluginSignatures registered.

$Manager->registerPlugin($SprayFireDemo);
$Manager->registerPlugin($YourApp);
$Manager->registerPlugin($Doctrine);

// --- OR ---

$Manager->registerPlugin($SprayFireDemo)->registerPlugin($YourApp)->registerPlugin($Doctrine);

// --- OR ---

$Manager->registerPlugins([$SprayFireDemo, $YourApp, $Doctrine]);

?>
```
