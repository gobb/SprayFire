## SprayFire\Dispatcher

The Dispatcher module's responsibility is ensuring that apps are initialized and that the appropriate objects are created and methods invoked to generate the appropriate response based on a `SprayFire\Http\Routing\RoutedRequest`. The module is split into 2 interfaces, one for taking care of initializing the app and the other for processing the response.

If the framework were a living being this would be its brain. This is what ties everything together and gets the stuff you see on the browser to show up. To say this is a critical component of the framework is an understatement.

---

## SprayFire\Dispatcher\FireDispatcher

### AppInitializer

This implementation takes care of getting autoloading for the routed app is setup and that the app's bootstrap is instantiated and invoked. It takes a look at the top-level namespace from the `SprayFire\Http\Routing\RoutedRequest` and does the following:

- Add `TopLevelNamespace` to `ClassLoader\Loader` under path `/install/app`. This means that if you instantiate `\TopLevelNamespace\Controller\Pages` autoloading will look for it in `/install/app/TopLevelNamespace/Controller/Pages`. Nifty!

- Looks for `TopLevelNamespace\Bootstrap`, verifies that it is a `SprayFire\Bootstrap\Bootstrapper` and invokes it. If either of the two checks fail an exception is thrown. An issue has been created to [make this behavior configurable](https://github.com/cspray/SprayFire/issues/124) in 0.2.0a.

### Dispatcher

This implementation is responsible for creating, through factories, the appropriate Controller and Responder to generate the response. It also triggers events along the way, some of those events being mapped back to Controller actions like `Controller::beforeAction()` and `Controller::afterAction`. Below is a list of all the events that are triggered during the dispatching process. Each event listed is also a constant found in the module.

- `SprayFire\Dispatcher\Events::BEFORE_CONTROLLER_INVOKED`
    This event is called before the controller action is invoked. By default the `SprayFire\Controller\Controller::beforeAction()` event is tied to this method. The target for this event is the Controller object the action will be invoked on.
- `SprayFire\Dispatcher\Events::AFTER_CONTROLLER_INVOKED`
    This event is called after the controller action is invoked. By default the `SprayFire\Controller\Controller::afterAction()` event is tied to this method. The target for this event is the Controller object the action was invoked on.
- `SprayFire\Dispatcher\Events::BEFORE_RESPONSE_SENT`
    This event is called before the Responder has generated the appropriate response. The target is the Responder being used. No default Callback is provided for this event.
- `SprayFire\Dispatcher\Events::AFTER_RESPONSES_SENT`
    This event is called after the Responder has generated the appropriate response. The target is the Responder used. No default Callback is provided for this event.

> Right now there are implications in the way the `SprayFire\Responder\FireResponder` implementations work. The callbacks BEFORE_RESPONSE_SENT and AFTER_RESPONSE_SENT aren't really all that useful. There is no API for getting or setting the response so not a lot can be done in either event. There is an issue for 0.2.0a that is looking at addressing this issue.