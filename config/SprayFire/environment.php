<?php

$developmentMode = true;

$defaultCharset = 'UTF-8';

$environment = array(
    'developmentMode' => $developmentMode,
    'defaultCharset' => $defaultCharset,
    'registeredEvents' => array(
        \SprayFire\Mediator\DispatcherEvents::AFTER_CONTROLLER_INVOKED => '',
        \SprayFire\Mediator\DispatcherEvents::AFTER_RESPONSE_SENT => '',
        \SprayFire\Mediator\DispatcherEvents::AFTER_ROUTING => '',
        \SprayFire\Mediator\DispatcherEvents::BEFORE_CONTROLLER_INVOKED => '',
        \SprayFire\Mediator\DispatcherEvents::BEFORE_RESPONSE_SENT => '',
        \SprayFire\Mediator\DispatcherEvents::BEFORE_ROUTING => ''
    ),
    'services' => array(
        'Logging' => array(
            'name' => 'SprayFire.Logging.FireLogging.LogDelegator',
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
                $LogDelegator = $Container->getService('SprayFire.Logging.FireLogging.LogDelegator');
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
                $Normalizer = new \SprayFire\Http\Routing\FireRouting\Normalizer();
                $getRoutesConfig = function() use ($Paths) {
                    return include $Paths->getConfigPath('SprayFire', 'routes.php');
                };
                $installDir = \basename($Paths->getInstallPath());
                return array($Normalizer, $getRoutesConfig(), $installDir);
            }
        ),
        'ControllerFactory' => array(
            'name' => 'SprayFire.Controller.FireController.Factory',
            'parameterCallback' => function() use ($ReflectionCache, $Container) {
                $LogDelegator = $Container->getService('SprayFire.Logging.FireLogging.LogDelegator');
                return array($ReflectionCache, $Container, $LogDelegator);

            }
        ),
        'ResponderFactory' => array(
            'name' => 'SprayFire.Responder.Factory',
            'parameterCallback' => function() use($ReflectionCache, $Container) {
                $LogDelegator = $Container->getService('SprayFire.Logging.FireLogging.LogDelegator');
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
        )
    )
);

return $environment;