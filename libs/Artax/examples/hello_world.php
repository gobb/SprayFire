#!/usr/bin/php
<?php

/*
 * --------------------------------------------------------------------
 * Specify debug mode; boot Artax.
 * --------------------------------------------------------------------
 */

define('ATREYU_DEBUG_LEVEL', 1);
require dirname(__DIR__) . '/Artax.php'; // hard path to bootstrap file

/*
 * --------------------------------------------------------------------
 * Specify an event listener; broadcast that event.
 * --------------------------------------------------------------------
 */

$notifier->push('hello', function(){ echo "Hello, world." . PHP_EOL; });
$notifier->notify('hello');
