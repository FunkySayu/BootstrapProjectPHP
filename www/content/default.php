<?php
/**
* @file default.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief Page appelée par défaut (accueil)
* @version 0.1
* @date 2014-11-13
*/

/**
 * Sample function, used to show an example of PHP helpers to format HTML.
 *
 * This function is only a small helper allowing to display quickly code. There
 * is ABSOLUTELY no point to put this in a function, except to show this as an
 * exemple of avoiding mixing HTML and PHP.
 *
 * @param $content content to display in <code> section.
 */
function c($content) {
    echo "<code>" . $content . "</code>";
}
?>

<div class="jumbotron">
    <h1>This is a project example</h1>
    <p>It might help you bootstraping your own project.</p>
    <p>In order to make links from pages to another page, you should use the <?php c('UI::page_link("page id")') ?></p>
    <p><a href="<?php echo UI::page_link("example"); ?>">Here is an example!</a></p>
</div>

<div id="content-container" class="col-sm-12 border-radius-10 main-wrapper">

    <div class="page-header">
        <h1>Basic understanding</h1>
    </div>

    <div class="row">
        <p>Actually, the project is quite simple.</p>
        <p>As you might see, on the root directory of this project, you have the following items:</p>
        <ul>
            <li>
                <?php c("config/") ?> contains static configuration file for the website, that may be
                considered as constants in the code. Those configuration variables are accessible through
                <?php c('Config::get("section", "key")') ?>. As an example, here is the website name:
                <?php echo Config::get("website", "name"); ?>.
            </li>
            <li>
                <?php c("content/") ?> contains php scripts generating content pages. You should make a
                separation between pure PHP code (which does <b>NOT</b> belong into <?php c("content/") ?>) and
                page generation PHP code (i.e. something printing fancy HTML) that belongs into this
                directory.
            </li>
            <li>
                <?php c("assets/") ?>, containing static data such as images, css scripts or js scripts.
            </li>
            <li>
                <?php c("lib/") ?> containing pure PHP code that is not related to HTML generation (this
                is important for sanitizing your website architecture!).
            </li>
            <li>
                <?php c("templates/") ?> containing HTML parts that may be included in several content.
                For example, you might did your awesome HTML/PHP code that prints many unicorns. If you
                want to include it into two different pages (so two different .php files in the directory
                content/), don't copy paste it! Put it into template, and include the template using
                <?php c('UI::include_template("unicorns")') ?>
            </li>
            <li>
                <?php c("index.php") ?> contains only the basic architecture of the rendered HTML page.
                PLEASE DO NOT WRITE ANYTHING INTO THIS ONE, unless you have to modify core HTML structure.
                You want to add headers? Put them into <?php c("templates/headers.php") ?>. You want to
                edit your navigation bar? Edit <?php c("templates/navigation.php") ?>.
            </li>
        </ul>
    </div>

    <div class="row">
        <p>Pages have to be added manually in order to keep the website secure. You can add them into
        <?php c("lib/init.php") ?>.</p>
        <p>Note that everywhere, you have acces to the configuration variables defined in ../config.ini.</p>
        <p>
            <?php c('Config') ?> class contains static configuration file for the website, that may be
            considered as constants in the code. Those configuration variables are accessible through
            <?php c('Config::get("section_key")') ?>. As an example, here is the website name, shown by
            <?php c('Config::get("site_name")') ?>:
            <?php echo Config::get("site_name"); ?>.
        </p>
    </div>
</div>
