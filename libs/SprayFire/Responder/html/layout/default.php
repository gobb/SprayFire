<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <link href="<?php echo $this->service('Paths')->getUrlPath('css', 'sprayfire.style.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo $this->service('Paths')->getUrlPath('css', 'font-awesome.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo $this->service('Paths')->getUrlPath('css', 'bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        </head>
        <body>
            <div id="content">
                <div id="header">
                    <div id="main-header">
                        <h1><a href="/SprayFire"><img src="<?php echo $this->service('Paths')->getUrlPath('images', 'sprayfire-logo-bar-75.png'); ?>" id="sprayfire-logo" alt="SprayFire logo" width="200" height="75" /></a> A PHP 5.3+ Framework</h1>
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