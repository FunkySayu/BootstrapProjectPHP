<?php
/** 
* @file index.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief Principale page du site web
* @version 0.1
* @date 2014-10-30
*/ 

// Bootstrap the PHP
require_once("lib/init.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <?php UI::include_template("headers"); ?>
    </head>
    <body>
        <!-- NAVIGATION -->
        <?php UI::include_template("navigation"); ?>

        <!-- CONTENT WRAPPER -->
        <div id="content" class="container">
            <?php UI::include_content(Config::get("current_page")); ?>
        </div>

        <!-- FOOTER -->
        <?php UI::include_template("footer"); ?>
    </body>
</html>
