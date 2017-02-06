<?php
/**
* @file header.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief Gestion complète de l'affichage du début du site jusqu'à la zone de contenu
* @version 0.2
* @date 2014-11-13
*
* Cette partie gère l'inclusion des en-têtes HTML (tout ce qui est contenu dans la balise
* <head>) ainsi que la partie supérieure du site (logo, menu...). Elle ouvre pour finir
* une balise <div> d'id content n'attendant que d'être remplie :)
*/

?>
<title><?php echo Config::get("avaible_pages")[Config::get("current_page")]; ?> - <?php echo Config::get("site_name"); ?></title>

<meta charset="<?php echo Config::get("site_charset"); ?>">
<meta name="description" content="<?php echo Config::get("site_long_description"); ?>">
<meta name="keywords" content="<?php echo Config::get("site_keywords"); ?>">

<link rel="shortcut icon" href="<?php echo UI::get_asset("images/favicon.ico"); ?>" />
<link rel="icon" type="image/x-icon" href="<?php echo UI::get_asset("images/favicon.ico"); ?>" />
<link rel="icon" type="image/png" href="<?php echo UI::get_asset("images/favicon.png"); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo UI::get_asset("css/bootstrap.min.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo UI::get_asset("css/global.css"); ?>" />
