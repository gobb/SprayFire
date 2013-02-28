## SprayFire\Http

**Dependencies:**

- SprayFire\Object

This module is a very simple, light abstraction of HTTP information availaible to PHP. Right now the implementations are very simple. In future versions of SprayFire there will be Response implementations and a more spec compliant implementation.

### SprayFire\Http\Request

Provides constants for common request methods and an API to retrieve URI, request headers, request method and the HTTP version requested.

### SprayFire\Http\RequestHeaders

Provides an abstraction to retrieve common header information like host, connection type, cache control, user agent, whether or not the request has the common AJAX header and other things.

### SprayFire\Http\Uri

Provides information about a URI according to the RFC 3986. This includes things like schema, authority, path and query.

## SprayFire\Http\FireHttp

**Dependencies:**

- SprayFire\Http
- SprayFire\CoreObject

This implementation by default parses the common $_SERVER keys available for most servers. Check out the [documentation on the $_SERVER superglobal](http://www.php.net/manual/en/reserved.variables.server.php) for more information. At construction time you can pass in your own array holding values for the expected keys if your server doesn't populate this information for some reason.

### SprayFire\Http\FireHttp\Uri

Provides access to the parts of the URI specified in RFC 3986. Here we map the $_SERVER index we look for in each method.

```php
<?php

use SprayFire\Http\FireHttp as FireHttp;

$Uri = new FireHttp\Uri();

// string with format: <$_SERVER['HTTP_HOST']>:<$_SERVER['REMOTE_PORT']>
$Uri->getAuthority();

// string from $_SERVER['REQUEST_URI']
$Uri->getPath();

// integer from $_SERVER['REMOTE_PORT'] or defaults to 80
$Uri->getPort();

// string of query parameters from URL $_SERVER['QUERY_STRING']
$Uri->getQuery();

// string representing whether the request was made over https $_SERVER['HTTPS'] or 'http'
$Uri->getSchema();

// boolean returns if $Foo is an instanceof SprayFire\Http\Uri and the __toString returns same
$Uri->equals($Foo);

// string representing the complete URI: https://www.example.com/path/for/things?query=param&foo=bar
(string) $Uri;

?>
```
### SprayFire\Http\FireHttp\RequestHeaders

Much like the `SprayFire\Http\RequestHeaders` the information returned from the API for this interface is parsed from expected $_SERVER indexes. You can pass in your own array with expected indexes at construction time to replace the default values.

```php
<?php

use SprayFire\Http\FireHttp as FireHttp;

$Headers = new FireHttp\RequestHeaders();

// string from $_SERVER['HTTP_ACCEPT_CHARSET']
$Headers->getAcceptCharset();

// string from $_SERVER['HTTP_ACCEPT_ENCODING']
$Headers->getAcceptEncoding();

// string from $_SERVER['HTTP_ACCEPT_LANGUAGE']
$Headers->getAcceptLanguage();

// string from $_SERVER['HTTP_ACCEPT']
$Headers->getAcceptType();

// string from $_SERVER['HTTP_CACHE_CONTROL']
$Headers->getCacheControl();

// string from $_SERVER['HTTP_CONNECTION']
$Headers->getConnectionType();

// string from $_SERVER['HTTP_HOST']
$Headers->getHost();

// string from $_SERVER['HTTP_REFERER']
$Headers->getReferer();

// string from $_SERVER['HTTP_USER_AGENT']
$Headers->getUserAgent();

// boolean from whether $_SERVER['HTTP_X_REQUESTED_WITH']
$Headers->isAjaxRequest();
?>
```
### SprayFire\Http\FireHttp\Request

The request implementation allows access to the Http\Uri, Http\RequestHeaders and the method and version for the given request. The only information parsed by the Request object itself is the HTTP method and version. However, you have access to all of the information the RequestHeaders and Uri provide.

```php
<?php

use \SprayFire\Http\FireHttp as FireHttp;

// This example will use $_SERVER by default

$Uri = new FireHttp\Uri();
$Headers = new FireHttp\RequestHeaders();
$Request = new FireHttp\Request($Uri, $Headers);

// GET or POST for most requests
$Request->getMethod();

// Likely 1.0 or 1.1
$Request->getVersion();

// Returns the URI path from Http\Uri
$Request->getUri->getPath();

// Returns the user agent set from Http\RequestHeaders
$Request->getHeaders->getUserAgent();

?>
```
