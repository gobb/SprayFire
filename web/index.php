<?php
/**
 * @file
 * @brief The primary intialization script for SprayFire
 */

$requestStartTime = \microtime(true);

/**
 * @var $installPath the directory libs, app and web directories are stored in
 */
$installPath = \dirname(__DIR__);

/**
 * @var $libsPath the directory the SprayFire framework and 3rd-party libs should
 *      be stored.
 */
$libsPath = $installPath . '/libs';

/**
 * @var $SprayFireContainer
 */
$SprayFireContainer = include $libsPath  . '/SprayFire/Bootstrap/bootstrap.php';

/**
 * @todo The following markup eventually needs to be moved into the default
 * template for HtmlResponder.
 */

// NOTE: The below code is a temporary measure until the templating system is in place

$PathGenerator = $SprayFireContainer->getObject('PathGenerator');
$PrimaryConfig = $SprayFireContainer->getObject('PrimaryConfig');

$styleCss = $PathGenerator->getUrlPath('css','sprayfire.style.css');
$sprayFireLogo = $PathGenerator->getUrlPath('images', 'sprayfire-logo-bar-75.png');

echo <<<HTML
<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <link href="{$styleCss}" rel="stylesheet" type="text/css" />
        </head>
        <body>
            <div id="content">
                <div id="header">
                    <h1><img src="{$sprayFireLogo}" id="sprayfire-logo" alt="SprayFire logo" width="200" height="75" /></h1>
                    <ul>
                        <li>ver: {$PrimaryConfig->SprayFire->version}</li>
                        <li>repo: <a href="http://www.github.com/cspray/SprayFire">http://www.github.com/cspray/SprayFire/</a></li>
                        <li>wiki: <a href="http://www.github.com/cspray/SprayFire/wiki/">http://www.github.com/cspray/SprayFire/wiki/</a></li>
                        <li>api docs: coming soon!</li>

                    </ul>
                </div>

                <div id="body">
                    <div id="main-content">
                    </div>
                </div>

                <div id="footer">
                    <p style="text-align:center;"><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> &copy; Charles Sprayberry 2011</p>
                </div>
            </div>
HTML;

if ($PrimaryConfig->app->{'development-mode'} === 'on') {
    $errors = 'Errors: ' . \print_r($SprayFireContainer->getObject('ErrorHandler')->getTrappedErrors(), true);
    $memUsage = 'Memory usage: ' . \memory_get_peak_usage() / (1000*1024) . ' mb';
    $runTime = 'Execution time: ' . (\microtime(true) - $requestStartTime) . ' seconds';
    $debugInfo = <<<HTML
            <div id="debug-info" style="margin-top:1em;border:2px solid black;padding:5px;font-family:monospace;">
                <ul>
                    <li>$errors</li>
                    <li>$memUsage</li>
                    <li>$runTime</li>
                </ul>
            </div>
HTML;

} else {
    $debugInfo = '';
}

echo <<<HTML
    {$debugInfo}
    </body>
</html>
HTML;
