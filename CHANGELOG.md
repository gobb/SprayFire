# Changelog

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
