## SprayFire\Http\Routing

**Dependencies:**

- SprayFire\Http
- SprayFire\Object

This module is responsible for matching a `SprayFire\Http\Request` to the appropriate controller, action and parameters that should be invoked for the particular request. The responsibilities of a framework's Routing module is gonna widely vary based on the philosophy of the framework and what they think Routing is supposed to do. To SprayFire routing is akin to asking for directions on a long road trip. You stop off at a gas station, go inside and ask for directions and then come back out and continue your trip. The only thing that the Routing module does is map a HTTP request to the appropriate object and method to invoke.

Note that this module is under the `SprayFire\Http` namespace. This is intentional and is intended to communicate that we are specifically focusing on routing based on HTTP information. If your concept of routing should include functionality that doesn't rely on HTTP information provided then you should implement your own app or plugin module.

### SprayFire\Http\Routing\Route

This interface represents the information needed to know what object should be instantiated, what method should be invoked and what arguments should be passed to the method. The primary benefit of this object is that we can easily ascertain what namespace the object belongs to as this information is not something that can be easily provided in the URL. Typically a Route should match a request URI based on some pattern check, typically a regular expression. This interface also facilitates passing along the pattern that this Route should be matched against.

### SprayFire\Http\Routing\RouteBag

This interface represents a data structure to hold Route objects, retrieve them and iterate over the collection of Routes. Primarily this implementation would be used when you want routing to be based off of some preconfigured Route objects instead of parsing a pretty URL.

### SprayFire\Http\Routing\RoutedRequest

This interface holds the information determined to be routed. It allows us access to the app namespace, the fully namespaced controller, method and arguments to use. There really isn't a lot going on in this implementation, just a simple data transfer object.

### SprayFire\Http\Routing\MatchStrategy

This interface holds an API to allow easily plugging in your own algorithm to determine what Route should be returned as matching the Request. The interface only provides one method `MatchStrategy::getRouteAndParameters()` that has two arguments a RouteBag and a `SprayFire\Http\Request`. An array should be returned with the keys 'Route', holding a 'Route' implementation, and 'parameters', holding a numeric or associative indexed array. The interface provides constants for these key values: `MatchStrategy::ROUTE_KEY` and `MatchStrategy::PARAMETER_KEY`.

### SprayFire\Http\Routing\Router

This is the primary interface of the API and handles determining what Route should be used, by utilizing injected MatchStrategy implementations and controls generating the appropriate RoutedRequest. It only requires implementing one method `Router::getRoutedRequest()` accepts 1 argument, a `SprayFire\Http\Request` object and should *always* return a RoutedRequest.

## SprayFire\Http\Routing\FireRouting

**Dependencies:**

- SprayFire\Http
- SprayFire\Http\Routing
- SprayFire\CoreObject
- SplObjectStorage

### SprayFire\Http\Routing\FireRouting\Route & RouteBag

The Route is quite simple to use and just needs information to satisfy the get* methods from the interface passed to constructor. The RouteBag is equally simple and is simply a data structure to hold Route implementations.

```php
<?php

use \SprayFire\Http\Routing\FireRouting as FireRouting;

$pattern = '/url/regex/pattern/to/match';
$namespace = 'YourApp.Controller';
$controller = 'Pages'; // optional defaults to Pages
$action = 'index'; // optional defaults to index
$method = 'GET';    // optional defaults to null

// If $method is passed non-null it is assumed to be an HTTP method and this method must
// be the one used for the request otherwise the route will not pass.

// It doesn't matter if the $NoMatchRoute pattern matches, it will always be returned
// if a Route couldn't be matched against. You don't have to provide this object but
// you can if you want.
$NoMatchRoute = new FireRouting\Route('/404', $namespace, 'NotFound', $action);
$Route = new FireRouting\Route($pattern, $namespace, $controller, $action, $method);

$RouteBag = new FireRouting\RouteBag($NoMatchRoute);
$RouteBag->addRoute($Route);

// When we iterate over $RouteBag we would get [0 => $Route]
// If we call $RouteBag->getRoute() with no pattern we get $NoMatchRoute

?>
```

### SprayFire\Http\Routing\FireRouting\ConfigurationMatchStrategy

This is the MatchStrategy implementation the framework provides as default for v0.1.0a. This strategy relies on the Route objects in the passed RouteBag. Check out the code examples above for the Route & RouteBag. The examples below are really more about the particular patterns that might match a Route and how this MatchStrategy matches it. If no Route is stored with a pattern that matches the Request the RouteBag's default Route is returned.

```php
<?php

use \SprayFire\Http\Routing\FireRouting as FireRouting;

// ConfigurationMatchStrategy takes a look at the HTTP request URI and the pattern
// associated to each Route in the RouteBag. The Routes created in each example are
// assumed to be stored in the RouteBag passed to the ConventionMatchStrategy

$uri = '/';
$Root = new FireRouting\Route('/', 'YourApp.Controller');
$About = new FireRouting\Route('/about/', 'YourApp.Controller', 'Pages', 'about');

// The $uri would match $Root in this example.

$uri = '/about';
$Root = new FireRouting\Route('/', 'YourApp.Controller');
$RightAbout = new FireRouting\Route('/about/', 'YourApp.Controller', 'Pages', 'about');
$WrongAbout = new FireRouting\Route('/about', 'YourApp.Controller', 'Pages', 'wrongAbout');

// Internally the ConfigurationMatchStrategy will always (1) prepend and append a '/'
// to the end of the URI path. So, even if the URI was requested with no trailing '/' it
// WILL have one when matching against your pattern. Additionally, the entire pattern
// '/^{$pattern}$/' is checked against. For this reason $RightAbout will be returned and
// not $WrongAbout in this example.

$uri = '/blog/posts/blog-title';
$Root = new FireRouting\Route('/', 'YourApp.Controller');
$BlogPosts  = new FireRouting\Route('/blog/posts/(?P<title>[A-Za-z_-]+)/', 'YourApp.Controller', 'Blog', 'posts');

// The URI would match $BlogPosts here, additionally the use of a named subgroup in the
// regex pattern allows us to capture named parameters in the URL and outside of GET or
// POST. The named parameter can be found in RoutedRequest::getParameters() which will
// return an associative array ['title' => 'blog-title'].

?>
```

> If you extend from `SprayFire\Controller\FireController\Base` you can alias the `RoutedRequest::getParameters()` function by `$this->parameters` in your controller methods.

### SprayFire\Http\Routing\FireRouting\ConventionMatchStrategy

> Note, as of v0.1.0a there is a flaw in how the Router implementation utilizes the MatchStrategy objects that would cause the controller and action to not be normalized properly in some situations. This could potentially cause the framework to not recognize the URI as a valid resource although it is. This is an issue with how MatchStrategy was implemented in the module late in development. There is a [github issue](https://github.com/cspray/SprayFire/issues/131) for v0.2.0a milestone that will resolve this.

This is an optional MatchStrategy provided by the framework that will turn a pretty URL into the appropriate action and parameters based on a commonly used convention in the PHP framework world. This MatchStrategy completely ignores the passed RouteBag and simply parses the URI for the Request. Check out how to pass default value options to the strategy and what happens with certain URIs.

```php
<?php

// You can pass options into the match strategy dictating default values to use if
// none were provided in the URI. It is good practice to always provide the namespace
// you expect your Controller implementations to fall into.

$strategyOptions = [
    'namespace' => 'YourApp.Controller', // default SprayFire.Controller.FireController
    'controller' => 'Pages', // default Pages
    'action' => 'index', // default index
    'installDirectory' => '' // if you expect install directory name in URL include that directory name here. default ''
];

$MatchStrategy = new FireRouting\ConventionMatchStrategy($strategyOptions);

?>
````

```php
<?php

// all examples will use defaults listed above

$uri = '/controller/action/params';
// $namespace = 'YourApp.Controller'
// $controller = 'controller'
// $action = 'action'
// $params = ['params']

$uri = '/controller/';
// $namespace = 'YourApp.Controller'
// $controller = 'controller'
// $action = 'index'
// $params = []

// You can mark a fragment as a parameter by preceding it with a ':'
$uri = '/controller/:param1/:param2';
// $namespace = 'YourApp.Controller'
// $controller = 'controller'
// $action = 'index'
// $params = ['param2', 'param2']

// As soon as one marked parameter is encountered all preceding fragments are
// considered to be parameters as well
$uri = '/:param1/param2/param3';  // notice only first param is marked
// $namespace = 'YourApp.Controller'
// $controller = 'Pages'
// $action = 'index'
// $params = ['param1', 'param2', 'param3']

// You can also have named parameters, just put the label on the left side of the ':'
$uri = '/blog/posts/title:some-title';
// $namespace = 'YourApp.Controller'
// $controller = 'blog'
// $action = 'posts'
// $params = ['title' => 'some-title']

?>
```

### SprayFire\Http\Routing\FireRouting\Router

This class takes a `SprayFire\Http\Request` and converts it into a `SprayFire\Http\Routing\RoutedRequest`. Much of the algorithm that determines how a Request is converted into a RoutedRequest lies in the `MatchStrategy` implementations and the `RouteBag`. Typically userland SprayFire applications won't make use of the Router and when it does there's only one method `Router::getRoutedRequest(\SprayFire\Http\Request $Request)`, ensure you return a `SprayFire\Http\Routing\RoutedRequest` and you're golden.

### SprayFire\Http\Routing\FireRouting\RoutedRequest



### SprayFire\Http\Routing\FireRouting\Normalizer

This class enables us to turn a URL friendly string into a full blown controller or action name, matching the expected coding styles for the framework. This is a rudimentary implementation and isn't designed for massive amounts of inflection or regular expression matching to try to guess what should and should be in a particular case. We simply trim all the spaces, convert dashes, underscores to spaces, upper case on words and return the string with the first letter lowercase or kept capitalized. It is really up to you to write clean controller and action URLs or implement your own Normalizer to use that will do more advanced string parsing. Let's take a look at some examples:

```php
<?php
$Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();

$basic = 'controller';
echo $Normalizer->normalizeController($basic);
// outputs Controller

$sprayFire = 'spray_fire';
echo $Normalizer->normalizeController($sprayFire);
// output SprayFire

$sprayFireDashes = 'spray-fire';
echo $Normalizer->normalizeController($sprayFireDashes);
// outputs SprayFire

$sprayFireNone = 'sprayfire';
echo $Normalizer->normalizeController($sprayFireNone);
// output Sprayfire

// action output is similar with the difference being the first letter is always lower case

?>
```
