<?php

$developmentMode = true;

$environment = array(
    'developmentMode' => $developmentMode,
    'services' => array(
        'Logging' => array(
            'name' => 'SprayFire.Logging.FireLogging.LogDelegator',
            'parameterCallback' => function() use($Paths) {
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
            'parameterCallback' => function() use($Paths) {
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
        )
    )
);

return $environment;