<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <link href="<?php echo $styleCss; ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo $fontAwesomeCss; ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo $twitterBootstrapCss; ?>" rel="stylesheet" type="text/css" />
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
                    <p style="text-align: center;">Icons are provided by Font Awesome - http://fortawesome.github.com/Font-Awesome</p>
                </div>
            </div>
        </body>
    </html>