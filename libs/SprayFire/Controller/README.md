## SprayFire\Controller

**Dependencies**
- SprayFire\Mediator
- SprayFire\Responder
- SprayFire\Responder\Template
- SprayFire\Service
- SprayFire\Object

This module is a core piece of the framework and determines what data, Responder and Templates will be used for a given request. Data is set in a context sensitive manner that will allow context sensitive escaping by the `SprayFire\Responder` module. It is also the responsibility of implementations to provide a `SprayFire\Responder\Template\Manager` that will be used to determine what templates should be rendered in which order.

Additionally implementation are also `SprayFire\Service\Consumer` implementations. You should take a look at the `SprayFire\Service` module to see how this works in SprayFire.

---

## SprayFire\Controller\FireController

**Dependencies**
- SprayFire\Controller
- SprayFire\Mediator
- SprayFire\Responder
- SprayFire\Responder\Template
- SprayFire\Service\FireService
- SprayFire\CoreObject

Primarily your apps should be concerned with `SprayFire\Controller\FireController\Base`. The other Controllers provided are used to show the installation welcome, SprayFire about pages and other browser viewable content provided during installation. You can change these around if you want, I just wouldn't recommend it.

The Base controller provides implementations for all of the methods from the `SprayFire\Controller\Controller` interface allowing you to inherit and keep Controller code in children classes specific to preparing resource for responding. It also provides a list of default services that we expect to find in the `SprayFire\Service\Container` provided by the framework. Below is a list of default provided services but first let's take a look at an example.

```php
<?php

namespace YourAppName\Controller;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\Controller\FireController as FireController;

class Controller extends FireController\Base {

    // After construct $this->YourAppModel is type YourAppName\Model\YourAppModel
    // If the service was not found in the container at construction time an exception
    // of type SprayFire\Service\Exception\ServiceNotFound is thrown

    public function __construct() {
        // $this->services['YourAppModel'] = '\YourAppName\Model\YourAppModel'; is equivalent
        $this->services['YourAppModel'] = 'YourAppName.Model.YourAppModel';
    }

    public function beforeAction(SFMediator\Event $Event) {
        // do whatever you need to do here before any action is invoked
        // for future compatibility reasons it is suggested you call parent::beforeAction($Event)
        parent::beforeAction($Event);
    }

    public function afterAction(SFMediator\Event $Event) {
        // do whatever you need to do here after any action is invoked
        // for future compatibility reasons it is suggested you call parent::afterAction($Event)
        parent::afterAction($Event);
    }

    // You should also add methods here that map back to actions from Routing

}

?>
```

Please note, that Controller implementations, **by design**, do not have access to the `SprayFire\Service\Container` object holding all the services available. To gain access to a service you must explicitly declare it in the `$services` associative array. It is highly recommended that you add them in `Controller::__construct()` such as in the example. Setting the property explicitly will result in default services not being provided and setting them after construction will have no effect.


### Default provided services from SprayFire\Controller\FireController\Base

`[Property => Service Type]`

- `Paths => \SprayFire\FileSys\FireFileSys\Paths`
- `Request => \SprayFire\Http\FireHttp\Request`
- `RoutedRequest => \SprayFire\Http\Routing\FireRouting\RoutedRequest`
- `Logging - \SprayFire\Logging\FireLogging\LogOverseer`
- `TemplateManager - \SprayFire\Responder\Template\FireTemplate\Manager`
