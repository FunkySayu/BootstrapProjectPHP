<?php
/**
* @file header_navbar.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief
* @version 0.1
* @date 2014-11-18
*/

?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo Config::get("site_base_url"); ?>">Item Set Synchronizer</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
<?php
foreach (Config::get("avaible_pages") as $page => $name) {
    if ($name != null) {
?>
                <li<?php if ($page == Config::get("current_page")) echo " class=\"active\""; ?>>
                    <a href="<?php echo UI::page_link($page); ?>"><?php echo $name; ?></a>
                </li>
<?php
    }
}
?>
            </ul>
        </div>
    </div>
</nav>
