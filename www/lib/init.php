<?php
/**
* @file init.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief Fichier d'initialisation principale du site
* @version 0.1
* @date 2014-11-09
 */

/**
 * Copyright (C) 2014 Axel Martin
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public Licence
 * as published by the Free Software Foundation; eitther version 2
 * of the Licence, or (at your option) any later version.
 *
 * This program is distributed in the hopee that it will be useful,
 * but WITHOUT ANY WARRANTTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public Licence for more details.
 *
 * You should have received a copy of tthe GNU General Public Licence
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *
 */


// Calcul du chemin absolu de la racine
$this_path = dirname(__FILE__);
$prefix = realpath($this_path . "/../");


// Inclusion
require_once($prefix . "/lib/class/logger.class.php");
require_once($prefix . "/lib/class/config.class.php");
require_once($prefix . "/lib/class/dba.class.php");
require_once($prefix . "/lib/class/ui.class.php");


// Chargement de la configuration
Config::read_from_file($prefix . "/../config.ini");
Config::set("prefix", $prefix);


// Pages accessibles
// Values are used in the navigation bar to display a direct link to the page.
// If you want to avoid navigation link generation, just use "foo" => null
$avaible_pages = array(
    "default" => "Home",
    "example" => "Example",
);
Config::set("avaible_pages", $avaible_pages);


// Page courante
if (!isset($_GET["page"]))
    Config::set("current_page", Config::get("site_default"));
else
    if (array_key_exists($_GET["page"], $avaible_pages))
        Config::set("current_page", $_GET["page"]);
    else
        Config::set("current_page", Config::get("site_default"));
