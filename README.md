
## Introduction

SprayFire is a modular, unit-tested, MVC-inspired approach to developing web applications using PHP 5.3+.

The primary purpose of SprayFire is to serve as a test-bed for learning more about PHP and the problems tackled by PHP frameworks.  We want to experiment, think outside of the box, and hopefully come up with a solution that we not only learn from but can produce great, sustainable apps with.

SprayFire is currently in development stage.  The API and implementations provided by the framework are completely subject to change and should not be relied upon at this point.

The [framework wiki](http://www.github.com/cspray/SprayFire/wiki/) is a more reliable, stable source for information on the current state of the framework and the future of SprayFire.

## Initial Features

- System built upon routing an HTTP request to a Controller and action.  That action gathers data for a Responder that generates and sends the response.
- Simple, easy-to-learn templating using built-in PHP functionality.
- HTML templating system ensuring that XSS attacks are not possible by escaping all output.
- Support for a wide-variety of PHP 5.3+ capable libraries.
- A version with a variety of popular ORMs to act as data source while you still roll-your-own Model.
- A command-line utility to set up your basic app directory structure and files; note that this is *not* scaffolding but simply creating a default SprayFire installation.
- Support for the [Composer](https://github.com/composer/composer) dependency manager tool

## Features to Come

- SprayFire DataAccess layer as an additional data abstraction system.
- A base Model class to allow for generic functionality.  Note, this is different than the Model layer as a whole.  For more info, please read [this blog post about the subject](http://cspray.github.com/2012/07/07/the-model-layer-and-class.html).

## Dependencies

- PHP 5.3+ and up.  Note that the bulk of development and testing has been on 5.3.8, 5.3.9 and 5.3.10.
- Out-of-the-box support for clean URLs using [Apache](http://httpd.apache.org/) [mod_rewrite](http://httpd.apache.org/docs/current/mod/mod_rewrite.html).  If this is not available to you then you will need to implement your own system of pretty URLs.
- SprayFire uses [SPL](http://www.php.net/manual/en/book.spl.php) exceptions and data structures so this extension will need to be enabled.
- Some of SprayFire required  configuration is done in [JSON](http://www.json.org/) and you will need the [appropriate PHP extension](http://www.php.net/manual/en/book.json.php).
- Unit tests are written with [PHPUnit](https://github.com/sebastianbergmann/phpunit).
- Class autoloading is taken care of by [ClassLoader](http://github.com/cspray/ClassLoader)

## github

SprayFire takes tremendous advantage of the simple, quality tools for project management provided by [github](http://www.github.com).  Below we discuss how SprayFire utilizes these tools and how you can gather the best information possible from those tools.

### The Wiki

[SprayFire Wiki](http://www.github.com/cspray/SprayFire/wiki/) is the absolute best source for all the information you could possibly want to know about the project.  Things like conventions, coding and documentation guidelines, how various aspects of SprayFire work and pretty much everything about the project.

Of particular note in the wiki are the [Conventions](http://www.github.com/cspray/SprayFire/wiki/Conventions/) page and the [Coding and Documentation Guideline](https://github.com/cspray/SprayFire/wiki/Coding-and-Documentation-Guideline), although you really should check out the entire thing.

### Issues and Milestones

The [issues](https://github.com/cspray/SprayFire/issues) and [milestones](https://github.com/cspray/SprayFire/issues/milestones) are the pulse and vision of the framework.  If you want to contribute to the project, after you've read through the wiki, then solving issues and completing milestones is the best place to start.

## Team

### Charles Sprayberry

- Lead Developer and Creator
- Benevolent Dictator for Life
- blog: [ramblings of a PHP enthusiast](http://cspray.github.com/)
- twitter: [@charlesspray](https://twitter.com/#!/charlesspray)

### Dyana Stewart

- Graphic Designer
- twitter: [@dy249](https://twitter.com/#!/Dy249)