## Build Status

- master branch: [![Build Status](https://secure.travis-ci.org/cspray/SprayFire.png?branch=master)](https://travis-ci.org/cspray/SprayFire)
- development branch: [![Build Status](https://travis-ci.org/cspray/SprayFire.png?branch=development)](https://travis-ci.org/cspray/SprayFire)

## Installation and configuration

A key priority in the development of SprayFire has been that it should be easy to install, configure and deploy. We have been focusing on the API and implementations provided by the framework at this point so there are some manual steps that we plan on automating at some point in the future.

### Installing SprayFire

```plain
mkdir sprayfire_apps && cd sprayfire_apps
git clone git://github.com/cspray/SprayFire.git
cd sprayfire_apps
git submodule init
git submodule update
```

If the install was successful you should have the [`cspray\ClassLoader`](https://github.com/cspray/ClassLoader) library in your libs folder. At this point the install process is complete. Check out the details below for configuration and setting up your app.

### Configure and setting up apps

Now that you've installed SprayFire you probably wanna start writing apps with it. First thing you should do is determine what name you want the top-level namespace of your app to be. Once you've decided on a name...

```plain
# expect you to be in the same SprayFire install path
cd app
mkdir YourAppName && cd YourAppName
touch Bootstrap.php
```

Now, open up `/sprayfire_apps/app/YourAppName/Bootstrap.php` and create a class similar to the following:

```php
<?php

namespace YourAppName;

class Bootstrap extends \SprayFire\Bootstrap\FireBootstrap\App {

    public function runBootstrap() {

    }

}
```

This abstract [`\SprayFire\Bootstrap\Bootstrapper`](https://github.com/cspray/SprayFire/blob/master/libs/SprayFire/Bootstrap/Bootstrapper.php) provides two protected properties: `Bootstrap::Container` and `Bootstrap::ClassLoader`. `Container` will be type [`\SprayFire\Service\Container`](https://github.com/cspray/SprayFire/blob/master/libs/SprayFire/Service/Container.php) and `ClassLoader` will be type [`\ClassLoader\Loader`](https://github.com/cspray/ClassLoader/blob/master/Loader.php). This allows you to add whatever services your Model, Controller or Responder layers may use and to setup autoloading for whatever third-party plugins or libraries you may be using.

If you're interested in setting up explicit routes you should [check out the Routing docs](https://github.com/cspray/SprayFire/wiki/Routing) and take a look at [`/sprayfire_apps/config/SprayFire/routes.php`](https://github.com/cspray/SprayFire/blob/master/config/SprayFire/routes.php). You can also take a look at the [`/sprayfire_apps/config/SprayFire/environment.php`](https://github.com/cspray/SprayFire/blob/master/config/SprayFire/environment.php) to adjust certain aspects of how the framework operates at runtime. You can also check out the [Configuration docs](https://github.com/cspray/SprayFire/wiki/Configuration) for more information.

## Writing your first app



## Team

### Charles Sprayberry

- Lead Developer and Creator
- Benevolent Dictator for Life
- blog: [ramblings of a PHP enthusiast](http://cspray.github.com/)
- twitter: [@charlesspray](https://twitter.com/#!/charlesspray)

### Dyana Stewart

- Graphic Designer
- twitter: [@dy249](https://twitter.com/#!/Dy249)
