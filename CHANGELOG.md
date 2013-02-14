# Changelog

## 0.1.0alpha

- Initial version
- Autoloading through [`ClassLoader`]() library
- Output escaping with [`Zend\Escaper`]() module
- [`\SprayFire\Bootstrap`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Bootstrap) module that will be used to startup plugins, apps and third-party libraries.
- [`\SprayFire\Controller`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Controller) module to allow setting of context-sensitive Responder data, which Responder type to use and the Templates that should be rendered.
- [`\SprayFire\Dispatcher`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Dispatcher) module that handles initializing apps and controls the process of invoking an action on a Controller.
- [`\SprayFire\Factory`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Factory) module that allows easy dynamic creation of objects.
- Absolute path creation for various file path contexts with [`\SprayFire\FileSys\PathGenerator`](https://github.com/cspray/SprayFire/blob/master/libs/SprayFire/FileSys/PathGenerator.php)
- [`\SprayFire\Http`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Http) module initially comes with API and implementations for HTTP information about the request.
- [`SprayFire\Http\Routing`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Http/Routing) module that will take an HTTP request and turn it into a controller and action that can be invoked to generate the appropriate response.
- [`\SprayFire\Logging`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Logging) module allows logging at 4 different priorities to a variety of loggers.
- [`\SprayFire\Mediator`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Mediator) module that allows creation of Callback objects and triggering of Events that will invoke associated Callbacks.
- [`\SprayFire\Responder`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Responder) module that takes the data provided by the Controller and generates the appropriate HTTP response.
- [`\SprayFire\Responder\Template`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Responder/Template) module that allows abstracting away templates rendered by the Responder.
- A [`\SprayFire\Service`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Service) module that allows easy assignment and retrieval of services for an application
- [`SprayFire\Validation`](https://github.com/cspray/SprayFire/tree/master/libs/SprayFire/Validation) module that allows chaining common validation rules and retrieving detailed information about the results of each check.
