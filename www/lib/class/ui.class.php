<?php
/**
* @file ui.class.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief gestion de l'interface utilisateur
* @version 0.1
* @date 2014-11-13
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


/**
* @author Axel Martin
* @brief Gestion de quelques éléments de l'interface utilisateur
* @version 0.1
* @date 2014-11-13
*
* La classe UI est là particulièrement en tant qu'accesseur rapide à certains
* éléments de l'interface utilisateur. Elle contient les appels principaux
* aux templates concevant la base du site.
*/
class UI {


    private static $_is_header_loaded = false;
    private static $_is_footer_loaded = false;


    /**
    * @author Axel Martin
    * @brief Affiche une page d'erreur 403 (en renvoyant le code HTTP correcte)
    * @version 0.1
    * @date 2014-11-13
    *
    * @param error éventuel message d'erreur à rajouter
    */
    public static function access_denied($error = "Access Denied") {
        header("HTTP/1.1 403 $error");
        require_once(Config::get("prefix") . "/templates/access_denied.php");
        exit;
    }


    /**
    * @author Axel Martin
    * @brief Permet de récupérer l'adresse d'un fichier statique
    * @version 0.1
    * @date 2014-11-13
    *
    * @param asset_name nom de l'element à récupérer
    * @return l'adresse de l'element
    */
    public static function get_asset($asset_path) {
        return Config::get("site_base_url") . "/assets/" . $asset_path;
    }


    /**
    * @author Axel Martin
    * @brief Permet l'inclusion d'un template par son nom
    * @version 0.1
    * @date 2014-11-13
    *
    * @param template_name le nom du template /!\ SANS LE .PHP /!\
    */
    public static function include_template($template_name) {
        require_once(Config::get("prefix") . "/templates/$template_name.php");
    }


    /**
    * @author Axel Martin
    * @brief Permet l'inclusion d'une page de contenu
    * @version 0.1
    * @date 2014-11-13
    *
    * @param template_name le nom du template /!\ SANS LE .PHP /!\
    */
    public static function include_content($page_name) {
        require_once(Config::get("prefix") . "/content/$page_name.php");
    }


    /**
    * @author Axel Martin
    * @brief permet d'avoir le lien vers une page
    * @version 0.1
    * @date 2014-11-18
    *
    * @param page page vers laquelle avoir le lien
    * @return
    */
    public static function page_link($page) {
        if (array_key_exists($page, Config::get("avaible_pages")))
            return Config::get("site_base_url") . "/?page=$page";
        else
            return Config::get("site_base_url");
    }

}
