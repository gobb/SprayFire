## SprayFire\Mediator

**Depdencies:**

- SprayFire\Object

This module is responsible for registering callbacks against events and then triggering those events to invoke the registered callbacks. Through this module you can hook into SprayFire's internal processing and also implement your own system of events. There are
3 interfaces provided by the module that all work together.

### SprayFire\Mediator\Mediator

This is the interface that controls the entire event triggering process. It stores Callback objects against a string event name and then invokes the appropriate Callbacks when `Mediator::triggerEvent()` is called with the appropriate event. Typically we would recommend that events always get assigned to some class constant but this is entirely up to the implementing code.

### SprayFire\Mediator\Callback

This is the interface that actually invokes some procedure when an event is triggered. It has 2 methods: `Callback::getEventName()` and `Callback::invoke()`. As you can tell, one returns the event the callback is registered to and the other kicks off the procedure. The `invoke()` function takes 1 parameter a `SprayFire\Mediator\Event` object.

### SprayFire\Mediator\Event

This is the interface that holds all the information that is pertinent to an event being triggered. Stuff like, the target object of the event, the name of the event and any arguments passed to it. It could easily be extended to include additional information like the timestamp the event was triggered and anything else you think is pertinent. Although we can't typehint we typically expect `$Target` to be an object by convention. Of course, your application is free to implement its own philosophy in this regard.

---

## SprayFire\Mediator\FireMediator

**Dependencies:**

- SprayFire\Mediator
- SprayFire\CoreObject
- IteratorAggregate
- ArrayIterator

Right now this module is implemented and functioning but at a very basic level. There are some improvements that are planned for the 0.2.0a milestone. These improvements will increase the flexibility of the module and make some aspects of it a little more user friendly. Right now one concrete implementation is really a weakness at this point, but its intended use hasn't been fully realized yet. We'll discuss that object first. It is the...

### SprayFire\Mediator\FireMediator\EventRegistry

This class manages the events that are valid to be triggered and what type those events are expecting. The idea behind this object is that you can implement type safety on events. If a certain event is triggered you can guarantee that a certain type will be used for that event. Right now the functionality isn't implemented and if an event is not registered an exception will be thrown. We don't really like this behavior but it is where it is at the moment. We plan on fixing this in 0.2.0a to make event type safety a more dynamic process that you can closely control.

Using the `EventRegistry` is quite simple.

```php
<?php

$Registry = new \SprayFire\Mediator\FireMediator\EventRegistry();

// Or you could pass '\YourApp\Event\TargetType'
$Registry->registerEvent('eventName', 'YourApp.Event.TargetType');

// Will return the event type as you passed it so 'YourApp.Event.TargetType'
$Registry->getEventTargetType();

?>
```

You can also pass an event name to `EventRegistry::hasEvent()` to determine if an event name has been registered. You can also iterate over the registry with `foreach()`. If an event is attempted to be registered multiple times an exception is thrown.

### SprayFire\Mediator\FireMediator\CallbackStorage

This object is responsible for managing the storage and retrieval of Callbacks associated to various events. By turning this functionality into its own object you can modify the Callbacks that will be invoked without ever having to have access to the Mediator. The EventRegistry takes advantage of this and ensures that as events are registered and unregistered the appropriate Callbacks are destroyed.

The API for this method is quite simple, allowing you to create and remove containers by event name. You can add, remove and fetch Callbacks Callbacks associated to an event name. Finally you can check if some container exists for an event. If an event does not have any container or callback stored against it an empty array will be returned.


The remaining classes are quite simple and interact with the objects above or are dummy objects to pass along information. Check out the source code docs for more information.

