## SprayFire\Bootstrap

The API for this module is quite simple. One interface and one method, `SprayFire\Bootstrap\Bootstrapper::runBootstrap()`. When invoked whatever startup or initialization processes for that particular implementation should be run. That's about it. `SprayFire\Bootstrap\NullObject` will satisfy the requirements of this interface but perform no operations. It's provided in case you want to implement a Factory that always returns a Bootstrapper instance.

---

## SprayFire\Bootstrap\FireBootstrap

Provided bootstraps in the SprayFire namespace are for the framework's optional, and often times configurable, startup processes. PHP ini setting and session starting are just two great examples of startup processes that would be provided as a Bootstrapper implementation. The framework provides an abstract `SprayFire\Bootstrap\FireBootstrap\Pluggable` that will allow access to the `SprayFire\Service\Container` for adding services and the `ClassLoader\Loader` for adding any autoloading your app might need.