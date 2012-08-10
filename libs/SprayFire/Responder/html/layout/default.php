<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <?php foreach($css as $path): ?>
            <link href="<?php echo $path; ?>" rel="stylesheet" type="text/css" />
            <?php endforeach; ?>
        </head>
        <body>
            <div id="content">
                <div id="header">
                    <div id="main-header">
                        <h1><a href="/SprayFire"><img src="<?php echo $sprayFireLogo; ?>" id="sprayfire-logo" alt="SprayFire logo" width="200" height="75" /></a> A PHP 5.3+ Framework</h1>
                    </div>
                </div>

                <div id="body">
                    <div id="main-content">
                        <?php echo $templateContent; ?>
                    </div>

                    <div id="sidebar">
                        <?php
                            if (isset($sidebarContent) && \is_array($sidebarData)) {
                                echo $this->render($sidebarContent, $sidebarData);
                            }
                        ?>
                    </div>
                </div>

                <div id="footer">
                    <p style="text-align:center;"><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> &copy; Charles Sprayberry 2012</p>
                    <p style="text-align: center;">Icons are provided by <a href="http://fortawesome.github.com/Font-Awesome">Font Awesome</a></p>
                </div>
            </div>
        </body>
    </html>