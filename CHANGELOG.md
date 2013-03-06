# Changelog

> All changes were implemented by Charles Sprayberry unless otherwise noted.

### 0.2.0alpha

- Upgrading ClassLoader lib to version 1.3.0
- SprayFire\Plugin module that abstracts away management of apps, plugins and third party libraries into a consolidated codebase.
- Refactored SprayFire\Dispatcher\AppInitializer into PluginInitializer in Plugin module. No longer interface backed, module specific helper object. Several Dispatcher\Exception implementations were removed or refactored as a result of this change.
- Environment configuration added to determine whether app plugin should be auto initialized.
- JavaNamespaceConverter from v0.1.0a has been turned into an interface + trait combo.
- SprayFire\Utils renamed to SprayFire\StdLib. Several concrete implementations that were under the SprayFire namespace have been moved to SprayFire\StdLib. This includes the SprayFire\CoreObject and SprayFire\ValueObject implementations.
- SprayFire\Dispatcher\Events moved out of module and renamed to SprayFire\Events. This "enum" class will store all events possibly triggered by SprayFire.
- SprayFire\Plugin module to handle the management of code third party to SprayFire. This includes apps, framework plugins, app plugins and any other code that is not part of the SprayFire core.

## 0.1.0alpha

- Autoloading through ClassLoader library.
- Output escaping with Zend\Escaper module.
- SprayFire\Bootstrap module that will be used to startup plugins, apps and third-party libraries.
- SprayFire\Controller module to allow setting of context-sensitive Responder data, which Responder type to use and the Templates that should be rendered.
- SprayFire\Dispatcher module that handles initializing apps and controls the process of invoking an action on a Controller.
- SprayFire\Factory module that allows easy dynamic creation of objects.
- SprayFire\FileSys module for easy absolute path creation.
- SprayFire\Http module initially comes with API and implementations for information about the HTTP request.
- SprayFire\Http\Routing module that will take an HTTP request and turn it into information about which namespace, controller and action should be invoked.
- SprayFire\Logging module allows logging at 4 different priorities to a variety of loggers.
- SprayFire\Mediator module that allows creation of Callback objects and triggering of Events that will invoke associated Callbacks.
- SprayFire\Responder module that takes the data provided by the Controller and generates the appropriate HTTP response.
- SprayFire\Responder\Template module that allows abstracting away templates rendered by the Responder.
- SprayFire\Service module that allows easy assignment and retrieval of services represented as objects.
- SprayFire\Validation module that allows chaining common validation rules and retrieving detailed information about the results of each check.
