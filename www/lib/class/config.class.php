<?php
/**
* @file config.class.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief Module de gestion de la configuration du site
* @version 0.1
* @date 2014-11-10
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
* @brief Classe de gestion de la configuration du site
* @version 0.1
* @date 2014-11-10
*
*/
class Config {

    private static $_config = array();

    /**
    * @author Axel Martin
    * @brief récupère la valeur d'une clée
    * @version 0.1
    * @date 2014-11-10
    *
    * @param key clé à récupérer
    * @return valeur de la clé
    */
    public static function get($key) {
        if (!array_key_exists($key, self::$_config)) {
            return null;
        }

        return self::$_config[$key];
    }


    /**
    * @author Axel Martin
    * @brief change la valeur de la clé
    * @version 0.1
    * @date 2014-11-10
    *
    * @param key clé à changer
    * @param value valeur à modifier
    */
    public static function set($key, $value) {
        self::$_config[$key] = $value;
    }


    /**
    * @author Axel Martin
    * @brief parse depuis un fichier .ini les valeurs de la configuration
    * @version 0.1
    * @date 2014-11-10
    *
    * @param path chemin vers le fichier .ini
    */
    public static function read_from_file($path) {
        if (!($ini_array = parse_ini_file($path, true))) {
            die("Unreadable ini file $path");
        }

        foreach ($ini_array as $section => $values) {
            foreach ($values as $key => $value) {
                self::set($section . "_" . $key, $value);
            }
        }
    }
}
