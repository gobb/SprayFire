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

This interface holds an API to allow easily plugging in your own algorithm to determine what Route should be returned as matching the Request. The interface only provides one method `MatchStrategy::getRouteAndParameters()` that has two arguments a RouteBag and a `SprayFire\Http\Request`. An array should be returned with the keys 'Route' and 'parameters'. The interface provides constants for these values: `MatchStrategy::ROUTE_KEY` and `MatchStrategy::PARAMETER_KEY`.

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

// $RouteBag->getRoutes() would return [$Route]

?>
```

### SprayFire\Http\Routing\FireRouting\ConfigurationMatchStrategy

This is the MatchStrategy implementation the framework provides as default for v0.1.0a. This strategy relies on the Route objects in the passed RouteBag. Check out the code examples above for the Route & RouteBag. The examples below are really more about the particular patterns that might be put into a Route and how this MatchStrategy matches it. If no Route is stored with a pattern that matches the Request the RouteBag's default Route is returned.

```php
<?php

use \SprayFire\Http\Routing\FireRouting as FireRouting;

// ConfigurationMatchStrategy takes a look at the HTTP request URI and the pattern
// associated to each Route int he RouteBag

$uri = '/';
$Root = new FireRouting\Route('/', 'YourApp.Controller');
$About = new FireRouting\Route('/about/', 'YourApp.Controller', 'Pages', 'about');

// The $uri would match $Root in this example.

$uri = '/about';
$Root = new FireRouting\Route('/', 'YourApp.Controller');
$RightAbout = new FireRouting\Route('/about/', 'YourApp.Controller', 'Pages', 'about');
$WrongAbout = new FireRouting\Route('/about', 'YourApp.Controller', 'Pages', 'about');

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

### SprayFire\Http\Routing\FireRouting\ConventionMatchStrategy

### SprayFire\Http\Routing\FireRouting\Router

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