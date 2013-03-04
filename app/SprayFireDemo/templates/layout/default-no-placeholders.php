<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <link href="/SprayFire/web/css/sprayfire.style.css" rel="stylesheet" type="text/css" />
        </head>
        <body>
            <div id="content">
                <div id="header">
                    <h1><img src="/SprayFire/web/images/sprayfire-logo-bar-75.png" id="sprayfire-logo" alt="SprayFire logo" width="200" height="75" /></h1>
                    <ul>
                        <li>ver: {''}</li>
                        <li>repo: <a href="http://www.github.com/cspray/SprayFire">http://www.github.com/cspray/SprayFire/</a></li>
                        <li>wiki: <a href="http://www.github.com/cspray/SprayFire/wiki/">http://www.github.com/cspray/SprayFire/wiki/</a></li>
                        <li>api docs: coming soon!</li>

                    </ul>
                </div>

                <div id="body">
                    <div id="main-content">
                        <?php echo $templateContent; ?>
                    </div>
                </div>

                <div id="footer">
                    <p style="text-align:center;"><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> &copy; Charles Sprayberry 2011</p>
                </div>
            </div>
        </body>
    </html>