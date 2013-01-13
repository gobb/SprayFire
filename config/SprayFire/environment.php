<?php

use \SprayFire\Dispatcher as SFDispatcher;

$developmentMode = true;
$defaultCharset = 'UTF-8';
$virtualHost = true;

$environment = array(
    'developmentMode' => $developmentMode,
    'defaultCharset' => $defaultCharset,
    'virtualHost' => $virtualHost,
    'registeredEvents' => array(
        SFDispatcher\Events::AFTER_CONTROLLER_INVOKED => '',
        SFDispatcher\Events::AFTER_RESPONSE_SENT => '',
        SFDispatcher\Events::AFTER_ROUTING => '',
        SFDispatcher\Events::BEFORE_CONTROLLER_INVOKED => '',
        SFDispatcher\Events::BEFORE_RESPONSE_SENT => '',
        SFDispatcher\Events::BEFORE_ROUTING => ''
    )
);

return $environment;
