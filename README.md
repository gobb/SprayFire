## Release Info

*SprayFire 0.1.0a*

master branch: [![Build Status](https://secure.travis-ci.org/cspray/SprayFire.png?branch=master)](https://travis-ci.org/cspray/SprayFire) development branch: [![Build Status](https://travis-ci.org/cspray/SprayFire.png?branch=development)](https://travis-ci.org/cspray/SprayFire)

## Requirements

> With the recent RFC to EOL PHP 5.3 in a way that only security fixes get in the team felt it was prudent to go ahead and start transitioning to 5.4. There are several improvements we'll be taking advantage of, if you were running 5.3 SprayFire code you will need to upgrade your install as that language version will no longer be supported.

- PHP 5.4+
- The [`ClassLoader`](https://github.com/cspray/ClassLoader) library.
- The [`Zend\Escaper`](https://github.com/zendframework/zf2/tree/master/library/Zend/Escaper) module from the [`Zend Framework 2`](https://github.com/zendframework/zf2) project.

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

> The rest of this document will refer to the directory you installed the framework in as 'sprayfire_apps'. This may be something different depending on the directory you chose for the installation.


If the install was successful you should have the [`cspray\ClassLoader`](https://github.com/cspray/ClassLoader) library in your libs folder. At this point the install process is complete. Check out the details below for configuration and setting up your app.

### Configuring SprayFire

There are 2 critical files when wanting to configure SprayFire's runtime behavior. The first is [`/sprayfire_apps/config/SprayFire/routes.php`]() which controls how URLs are converted into the appropriate controllers and actions. You can read more about that configuration file and routing by looking at the [Routing docs](https://github.com/cspray/SprayFire/wiki/HTTP-and-Routing).

The second is [`/sprayfire_apps/config/SprayFire/environment.php`]() which controls how SprayFire's runtime behavior. Various aspects of the framework are directly controlled from this file including things like: setting development or production environment modes, the default charset to use, whether to use Virtual Host support and what framework provided bootstraps should be ran.

Initially the most important of the environment configuration values is whether or not Virtual Host support is enabled. For the [`\SprayFire\FileSys\PathGenerator`] implementations to provide the appropriate URL path used for JavaScript and CSS resources in HTML we have to know whether or not the framework has been setup to not require the install directory to load the framework. For more information check out the [Configuration guide docs]().

## Writing SprayFire driven Apps

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

// Note the top level namespace is the same name of your app
// Note that the name of the class is 'Bootstrap' and its parent class
class Bootstrap extends \SprayFire\Bootstrap\FireBootstrap\App {

    public function runBootstrap() {

    }

}
```

The parent class is an abstract [`\SprayFire\Bootstrap\Bootstrapper`](https://github.com/cspray/SprayFire/blob/master/libs/SprayFire/Bootstrap/Bootstrapper.php) that provides two protected properties: `Container` and `ClassLoader`. `Container` will be type [`\SprayFire\Service\Container`](https://github.com/cspray/SprayFire/blob/master/libs/SprayFire/Service/Container.php) and `ClassLoader` will be type [`\ClassLoader\Loader`](https://github.com/cspray/ClassLoader/blob/master/Loader.php). This allows you to add whatever services your Model, Controller or Responder layers may use and to setup autoloading for whatever third-party plugins or libraries you may be using.

At this point you can start creating Model, Controller and Responder directories and start wiring up your application.

## Team

### Charles Sprayberry

- Lead Developer and Creator
- Benevolent Dictator for Life
- blog: [ramblings of a PHP enthusiast](http://cspray.github.com/)
- twitter: [@charlesspray](https://twitter.com/#!/charlesspray)

### Dyana Stewart

- Graphic Designer
- twitter: [@dy249](https://twitter.com/#!/Dy249)
