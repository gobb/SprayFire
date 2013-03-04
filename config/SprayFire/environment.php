<?php

$developmentMode = true;
$defaultCharset = 'UTF-8';
$virtualHost = true;
$autoInitializeApp = false;

$environment = [
    'developmentMode' => $developmentMode,
    'defaultCharset' => $defaultCharset,
    'virtualHost' => $virtualHost,
    'autoInitializeApp' => $autoInitializeApp,
    'registeredEvents' => [
        \SprayFire\Events::AFTER_CONTROLLER_INVOKED => '',
        \SprayFire\Events::AFTER_RESPONSE_SENT => '',
        \SprayFire\Events::BEFORE_CONTROLLER_INVOKED => '',
        \SprayFire\Events::BEFORE_RESPONSE_SENT => ''
    ]
];

return $environment;
