<?php

$developmentMode = true;
$defaultCharset = 'UTF-8';
$virtualHost = true;

$environment = array(
    'developmentMode' => $developmentMode,
    'defaultCharset' => $defaultCharset,
    'virtualHost' => $virtualHost,
    'registeredEvents' => array(
        \SprayFire\Events::APP_LOAD => '',
        \SprayFire\Events::AFTER_CONTROLLER_INVOKED => '',
        \SprayFire\Events::AFTER_RESPONSE_SENT => '',
        \SprayFire\Events::BEFORE_CONTROLLER_INVOKED => '',
        \SprayFire\Events::BEFORE_RESPONSE_SENT => '',
    )
);

return $environment;
