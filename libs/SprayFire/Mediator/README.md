## SprayFire\Mediator

**Depdencies:**

- SprayFire\Object

This module is responsible for registering callbacks against events and then triggering those events to invoke the registered callbacks. Through this module you can hook into SprayFire's internal processing and also implement your own system of events. There are
3 interfaces provided by the module that all work together.

Note that this module is not intended to implement the observer pattern but is intended solely as a way to trigger events at certain times throughout the response gathering process.

### SprayFire\Mediator\Mediator

This is the interface that controls the entire event triggering process. It stores Callback objects against a string event name and then invokes the appropriate Callbacks when `Mediator::triggerEvent()` is called with the appropriate event. Typically we would recommend that events always get assigned to some class constant but this is entirely up to the implementing code.

### SprayFire\Mediator\Callback

This is the interface that actually invokes some procedure when an event is triggered. It has 2 methods: `Callback::getEventName()` and `Callback::invoke()`. As you can tell, one returns the event the callback is registered to and the other kicks off the procedure. The `invoke()` function takes 1 parameter a `SprayFire\Mediator\Event` object.

The idea behind this interface is that it allows us to implement any kind of procedure and know the parameters it expects. Ultimately this provides a lot of flexibility for how you implement the procedures. You can pass in a function name, an anonymous function or just implement your own concrete callback that directly takes care of the needed procedure.

### SprayFire\Mediator\Event

This is the interface that holds all the information that is pertinent to an event being triggered. Stuff like, the target object of the event, the name of the event and any arguments passed to it. It could easily be extended to include additional information like the timestamp the event was triggered and anything else you think is pertinent. Although we can't typehint we typically expect `$Target` to be an object by convention. Of course, your application is free to implement its own philosophy in this regard.

---

## SprayFire\Mediator\FireMediator

**Dependencies:**

- SprayFire\Mediator
- SprayFire\Mediator\Exception
- SprayFire\CoreObject
- IteratorAggregate
- ArrayIterator
- InvalidArgumentException

Right now this module is implemented and functioning but at a very basic level. There are some improvements that are planned for the 0.2.0a milestone. These improvements will increase the flexibility of the module and make some aspects of it a little more user friendly. Right now one concrete implementation is really a weakness at this point, but its intended use hasn't been fully realized yet. We'll discuss that object first. It is the...

### SprayFire\Mediator\FireMediator\EventRegistry

This class manages the events that are valid to be triggered and what type those events are expecting. The idea behind this object is that you can implement type safety on events. If a certain event is triggered you can guarantee that a certain type will be used as a target for that event. Right now the functionality isn't implemented and if an event is not registered an exception will be thrown. We don't really like this behavior but it is where it is at the moment. We plan on fixing this in 0.2.0a to make event type safety a more dynamic process that you can closely control.

Using the `EventRegistry` is quite simple.

```php
<?php

use \SprayFire\Mediator\FireMediator as FireMediator;

$CallbackStorage = new FireMediator\CallbackStorage();
$Registry = new FireMediator\EventRegistry($CallbackStorage);

// Or you could pass '\YourApp\Event\TargetType'
$Registry->registerEvent('eventName', 'YourApp.Event.TargetType');

// Will return the event type as you passed it so 'YourApp.Event.TargetType'
$Registry->getEventTargetType('eventName');

// The event registry hooks into the CallbackStorage used for the instantiated Mediator
// to ensure that registered events properly create containers so that callbacks can
// be easily added to and from.

// In the example a callback container is created for eventName

?>
```

You can also pass an event name to `EventRegistry::hasEvent()` to determine if an event name has been registered. You can also iterate over the registry with `foreach()`. If an event is attempted to be registered multiple times an exception is thrown.

### SprayFire\Mediator\FireMediator\CallbackStorage

This object is responsible for managing the storage and retrieval of Callbacks associated to various events. By turning this functionality into its own object you can modify the Callbacks that will be invoked without ever having to have access to the Mediator. Here's how to use the CallbackStorage object:

```php
<?php

use \SprayFire\Mediator\FireMediator as FireMediator;

$Storage = new FireMediator\CallbackStorage();

$Storage->createContainer('eventName');
$Storage->getCallbacks('eventName'); // returns []

// both of these callbacks are for 'event_name' event
$CallbackOne = new FireMediator\Callback(/* ... */);
$CallbackTwo = new FireMediator\Callback(/* ... */);

$Storage->addCallback($CallbackOne);
$Storage->addCallback($CallbackTwo);

$Storage->getCallbacks('eventName); // returns [$CallbackOne, $CallbackTwo]

$Storage->removeCallback($CallbackOne);
$Storage->getCallbacks('eventName'); // returns [$CallbackTwo]

$Storage->removeContainer('event_name');
$Storage->getCallbacks('eventName'); // returns []

?>
```

### SprayFire\Mediator\FireMediator\Callback

This object allows you to pass in a function at construction associated to a given event name. Anything that would pass an `is_callable()` check can be passed in as the function to invoke. This function should expect one argument passed to it, the `Event` being triggered.

```php
<?php

use \SprayFire\Mediator as SFMediator;
use \SprayFire\Mediator\FireMediator as FireMediator;

// A callback passed as a string as the function name
function foo(SFMediator\Event $Event) {
    // do the foo thing
}
$FooFunc = new FireMediator\Callback('fooEvent', 'foo');

// A callback passed as an anonymous function
$anonymousFoo = function(SFMediator\Event $Event) {
    // do an anonymous foo
};
$AnonFooFunc = new FireMediator\Callback('fooEvent', $anonymousFoo);


class CallbackClass {

    public static function staticFoo(SFMediator\Event $Event) {
        // do a static class foo
    }

    public function foo(SFMediator\Event $Event) {
        // do an object foo
    }



}

$FooStaticFunc = new FireMediator\Callback('fooEvent', 'CallbackClass::staticFoo');
$FooObjectFunc = new FireMediator\Callback('fooEvent', [(new CallbackClass), 'foo']);
?>
```

### SprayFire\Mediator\FireMediator\Mediator

This implementation provides a mechanism to add callbacks to the storage, although this can be done without the use of the Mediator, and provides a way to trigger callbacks for certain events. As of v0.1.0a this implementation will not trigger events for events that are not registered. In fact, an exception will be thrown if you attempt to add a callback for an event or trigger an event that is not registered. We are planning on changing this behavior in v0.2.0a. But, for now an example of implementing your own custom events is below.

```php
<?php

use \SprayFire\Mediator\FireMediator as FireMediator;

$Storage = new FireMediator\CallbackStorage();
$Registry = new FireMediator\EventRegistry($Storage);
$Mediator = new FireMediator\Mediator($Registry, $Storage);

// Note that in SprayFire driven apps utilizing the Pluggable bootstrap the
// framework's Mediator can be accessed in the Container under name:
// SprayFire.Mediator.FireMediator.Mediator

$Registry->registerEvent('your_event_name', '');

$Callback = new FireMediator\Callback('your_event_name', function($Event) { /* ... */ });
$Mediator->addCallback($Callback);

$Mediator->triggerEvent('your_event_name', $Foo, ['args']);

?>
```