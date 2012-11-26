<?php

use \SprayFire\Dispatcher as SFDispatcher;

$developmentMode = true;

$defaultCharset = 'UTF-8';

$environment = array(
    'developmentMode' => $developmentMode,
    'defaultCharset' => $defaultCharset,
    'registeredEvents' => array(
        SFDispatcher\Events::AFTER_CONTROLLER_INVOKED => '',
        SFDispatcher\Events::AFTER_RESPONSE_SENT => '',
        SFDispatcher\Events::AFTER_ROUTING => '',
        SFDispatcher\Events::BEFORE_CONTROLLER_INVOKED => '',
        SFDispatcher\Events::BEFORE_RESPONSE_SENT => '',
        SFDispatcher\Events::BEFORE_ROUTING => ''
    ),
    'services' => array(
        'Logging' => array(
            'name' => 'SprayFire.Logging.FireLogging.LogOverseer',
            'parameterCallback' => function() {
                $EmergencyLogger = new \SprayFire\Logging\FireLogging\SysLogLogger();
                $ErrorLogger = new \SprayFire\Logging\FireLogging\ErrorLogLogger();
                $InfoLogger = new \SprayFire\Logging\FireLogging\DevelopmentLogger();
                $DebugLogger = new \SprayFire\Logging\FireLogging\DevelopmentLogger();
                return array($EmergencyLogger, $ErrorLogger, $DebugLogger, $InfoLogger);
            }
        ),
        'Handler' => array(
            'name' => 'SprayFire.Handler',
            'parameterCallback' => function() use($Container, $developmentMode) {
                $LogDelegator = $Container->getService('SprayFire.Logging.FireLogging.LogOverseer');
                return array($LogDelegator, $developmentMode);
            }
        ),
        'HttpRequest' => array(
            'name' => 'SprayFire.Http.FireHttp.Request',
            'parameterCallback' => function() {
                $Uri = new \SprayFire\Http\FireHttp\Uri();
                $RequestHeaders = new \SprayFire\Http\FireHttp\RequestHeaders();
                return array($Uri, $RequestHeaders);
            }
        ),
        'HttpRouter' => array(
            'name' => 'SprayFire.Http.Routing.FireRouting.Router',
            'parameterCallback' => function() use ($Paths) {
                $getRouteBag = function() use ($Paths) {
                    return include $Paths->getConfigPath('SprayFire', 'routes.php');
                };
                $Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
                $installDir = \basename($Paths->getInstallPath());
                return array($getRouteBag(), $Normalizer, $installDir);
            }
        ),
        'ControllerFactory' => array(
            'name' => 'SprayFire.Controller.FireController.Factory',
            'parameterCallback' => function() use ($ReflectionCache, $Container) {
                $LogDelegator = $Container->getService('SprayFire.Logging.FireLogging.LogOverseer');
                return array($ReflectionCache, $Container, $LogDelegator);
            }
        ),
        'ResponderFactory' => array(
            'name' => 'SprayFire.Responder.FireResponder.Factory',
            'parameterCallback' => function() use($ReflectionCache, $Container) {
                $LogDelegator = $Container->getService('SprayFire.Logging.FireLogging.LogOverseer');
                return array($ReflectionCache, $Container, $LogDelegator);
            }
        ),
        'Mediator' => array(
            'name' => 'SprayFire.Mediator.FireMediator.Mediator',
            'parameterCallback' => function() use($Container) {
                return array($Container->getService('SprayFire.Mediator.FireMediator.EventRegistry'));
            }
        ),
        'EventRegistry' => array(
            'name' => 'SprayFire.Mediator.FireMediator.EventRegistry',
            'parameterCallback' => null
        ),
        'OutputEscaper' => array(
            'name' => 'SprayFire.Responder.FireResponder.OutputEscaper',
            'parameterCallback' => function() {
                return array('utf-8');
            }
        ),
        'TemplateManager' => array(
            'name' => 'SprayFire.Responder.FireResponder.FireTemplate.Manager',
            'parameterCallback' => null
        )
    )
);

return $environment;