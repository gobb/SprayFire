<?php

// This is a convenience mechanism for testing to get the directory that libs, app
// and web directory is stored in
defined('SPRAYFIRE_ROOT') or define('SPRAYFIRE_ROOT', \dirname(__DIR__));


include \SPRAYFIRE_ROOT . '/libs/ClassLoader/Loader.php';
$ClassLoader = new \ClassLoader\Loader();
$ClassLoader->registerNamespaceDirectory('SprayFire', \SPRAYFIRE_ROOT . '/libs');
$ClassLoader->registerNamespaceDirectory('SprayFireTest', \SPRAYFIRE_ROOT . '/tests');
$ClassLoader->registerNamespaceDirectory('Zend', \SPRAYFIRE_ROOT . '/libs');
$ClassLoader->setAutoloader();
