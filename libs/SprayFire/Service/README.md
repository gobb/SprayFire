## SprayFire\Service

**Dependencies**
- SprayFire\Object

The `SprayFire\Service` module is responsible for creating and providing services to various parts of your application. A service in SprayFire parlance is an object that provides some kind of functionality to the app. Services can be anything that you need to share across the application, from the Model to utility objects. This module handles what is a controversial functionality in the PHP framework world. Below is an overview of how the API is supposed to work, how it is supposed to be interacted with and some reasons why the API was chosen.

### SprayFire\Service\Container

This interface is responsible for storing and retrieving various services that might be needed by the framework or your application. You should be able to pass in a string class name or an actual object. When the string class name is passed back into the `Container::getService()` method the corresponding object should be returned. Ultimately, this is a pretty simple API and just serves as a container of services we can pass around and ensure same services get used everywhere.

### SprayFire\Service\Consumer

One of the concerns with the design approach of the `SprayFire\Service\Container` is that we want to avoid just passing it around to all the things. Everything shouldn't have access to the `Container` and when services are needed they should be explicitly requested. The first, and most immediate, answer here is just to use traditional [Dependency Injection](http://stackoverflow.com/questions/130794/what-is-dependency-injection). However, being able to dynamically provide that kind of solution would require knowing the dependencies present in virtually any app object created by the framework. This is a daunting task and would require an extensive use of Reflection. Instead we declare dependencies through the `SprayFire\Service\Consumer` interface. Consumer implementations should almost always be created by a factory or builder object to ensure appropriate services are added.

The interface has 2 methods. `Consumer::getServices()`, which should return an associative array of `[keys => className]` for the services required by that consumer. The second method, `Consumer::giveService()` should accept the string key from array of services and the object representing the `className`. If the service requested does not exist in the container an exception should be thrown.

So, that's what the module does. Here's why we did it:

#### Pros

- Dependencies (services) are "explicitly" declared from return value of `Consumer::getServices()`. Although it isn't explicit in the sense that the dependencies are listed in the constructor it is explicit in the sense that the Container isn't just blindly passed to everything. A service must be explicitly requested.
- Easily testable. Can isolate when you are testing a module's integration with a service. Simply mock the appropriate object and give the Consumer the mock object for the key it expects. You can just provide the services under test.
- Doesn't rely on heavy dependence of Reflection to create appropriate services. Although Reflection is used it can be avoided and only used when absolutely necessary to appropriately dynamically create the requested service.

#### Cons

- Requires a factory or builder implementation to create appropriate Consumer objects to ensure the services are added and failures are handled appropriately. This isn't that bad of a concern but it is something that must be considered.
- Dependencies aren't truly explicit and will not cause code to stop executing if a service is not provided. This has implications that a large majority of processing might occur and, for whatever reason, the service key expecting to hold an object does not and the code fatally quits. With true Dependency Injection this isn't nearly as big a concern.
- Services required are not part of the constructor parameters and cannot be typehinted or worked with like you would normal injections.

---

## SprayFire\Service\FireService

**Dependencies**
- SprayFire\Factory\FireFactory
- SprayFire\Logging
- SprayFire\Service
- SprayFire\Utils
- SprayFire\CoreObject
- BadMethodCallException
- InvalidArgumentException

### SprayFire\Service\FireService\Container

The responsibilities for the implementation were detailed in the parent module, `SprayFire\Service`. This part of the documentation will show some code examples for the different ways you can add a service and then retrieve that service.

> A word about namespaces. The framework typically passes class names as strings in dot-separated syntax instead of the normal PHP backslash namespace separator. For example, `\SprayFire\Service\Foo` could be requested from the container with the string `SprayFire.Service.Foo`, both are equivalent and result in same output.

**Adding service with no dependencies, passing service as string name**

---

**YourApp/Helper/Foo.php**

```php
<?php

namespace YourApp\Helper;

class Foo {

    // notice no dependencies
    public function __construct() {

    }

}

?>
```

**YourApp/Bootstrap.php**

```php
<?php

namespace YourApp;

class Bootstrap extends \SprayFire\Bootstrap\FireBootstrap\Pluggable {

    public function runBootstrap() {
        $this->Container->addService('YourApp.Helper.Foo');
    }

}

?>
```

Now passing the strings `'\YourApp\Helper\Foo'` or `'YourApp.Helper.Foo'` to `Container::getService()` will return a freshly created instance of type `\YourApp\Helper\Foo`. Services added as string names are lazy loaded and only created when the service is first retrieved. After first retrieval a cached copy of the service is returned ensuring all service Consumers are using the appropriate object.

**Adding service with some dependencies in constructor, passing service as string name**

---

**YourApp/Helper/BetterFoo.php**

```php
<?php

namespace YourApp\Helper;

class BetterFoo extends Foo {

    public function __construct(YourApp\Helper\DooHickey $DooHickey, $bar = null) {

    }

}

?>
```

**YourApp/Bootstrap.php**

```php
<?php

namespace YourApp;

class Bootstrap extends \SprayFire\Bootstrap\FireBootstrap\Pluggable {

    public function runBootstrap() {
        $this->Container->addService('YourApp.Helper.BetterFoo', function() {
            // It is your responsibility to ensure the appropriate object
            // dependencies are created!
            $DooHickey = new \YourApp\Helper\DooHickey();
            $bar = 'foobar';
            return [$DooHickey, $bar];
        });
    }

}

?>
```

Now when you retrieve the service the closure passed will use the returned array to pass as arguments to the constructor. The index order of the array should match the index order of the constructor. Like before everything is lazy loaded, so the dependencies won't be created until the first object is created and then the function will not be invoked again.

**Adding service as object**

---

Let's use the example before with `YourApp\Helper\BetterFoo`. Extending on that class and replacing bootstrap with...

**YourApp/Bootstrap.php**

```php
<?php

namespace YourApp;

class Bootstrap extends \SprayFire\Bootstrap\FireBootstrap\Pluggable {

    public function runBootstrap() {
        $DooHickey = new \YourApp\Helper\DooHickey();
        $Foo = new \YourApp\Helper\BetterFoo($DooHickey, 'bar');
        $this->Container->addService($Foo);
    }

}

?>
```

Like before you can access this object by passing either `'YourApp.Helper.BetterFoo` or `\YourApp\Helper\BetterFoo` to `Container::getService()`. If you're pretty sure that the service will be used throughout the normal course of the application processing this is the recommended method for adding services. By manually instantiating services that are highly used you skip a Reflection step in creating them dynamically.

**Adding service to be created by Factory**

---

Let's use the previous `BetterFoo` class from 'Adding service with some dependencies in constructor'. We're gonna have this service created by a Factory.

**YourApp/Bootstrap.php**

```php
<?php

namespace YourApp;

class Bootstrap extends \SprayFire\Bootstrap\FireBootstrap\Pluggable {

    public function runBootstrap() {
        // This should be of type \SprayFire\Factory\Factory
        $FooFactory = new \YourApp\Helper\FooFactory();
        $this->Container->registerFactory('fooFactory', $FooFactory);
        $parameters = function() {
            return [new \YourApp\Helper\DooHickey(), 'bar'];
        };
        $this->Container->addService('BetterFoo', $parameters, 'fooFactory');
    }

}

?>
```

Now when you pass `'BetterFoo'` to `Container::getService()` the returned object will be created by `$FooFactory`. This also provides some interesting behavior in regards to the service name. Previous examples passed some string that can be mapped by the container back to a class name. This is not the case with objects created by Factory based on behavior of the Factory. This is an intentional design decision to allow you flexibility in how services are added to the container.