<?php
/**
* @file logger.class.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief système de logging utilisé dans le site
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


define("DEBUG_LOG_LEVEL", 10);
define("INFO_LOG_LEVEL", 20);
define("WARNING_LOG_LEVEL", 30);
define("ERROR_LOG_LEVEL", 40);
define("CRITICAL_LOG_LEVEL", 50);
define("DEFAULT_FAILURE_CODE_LOG_LEVEL", 40);


/**
* @author Axel Martin
* @brief Gestion du logging simplifié pour le site Item Set Synchronizer
* @version 0.1
* @date 2014-11-10
*
*/
class Logger {

    private static $_failure_code = array();

    private static $_log_file = null;
    private static $_levels = array(
        "dbug" => 10,
        "info" => 20,
        "warn" => 30,
        "err " => 40,
        "crit" => 50
    );


    /**
    * @author Axel Martin
    * @brief Ecrit une entrée dans le fichier de logging
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message
    */
    private static function write($message) {
        if (self::$_log_file == null) {
            self::$_log_file = Config::get("logger_file");
            if (self::$_log_file == null) {
                die("No file to write in.");
            }
        }

        $file = fopen(self::$_log_file, "a+") or die("Unable to open file " . self::$_log_file);
        fwrite($file, $message."\n");
        fclose($file);
    }


    /**
    * @author Axel Martin
    * @brief Ecrit un message standardisé en vérifiant si son niveau de logging
    * est équivalent à celui demandé
    * @version 0.1
    * @date 2014-11-10
    *
    * @param prefix prefix du niveau de logging (dbug, info, warn...)
    * @param message message suivant le log
    */
    private static function write_standardized_log($prefix, $message) {
        if (!(self::$_levels[$prefix] >= Config::get("logger_level"))) {
            return;
        }

        if (!isset($_SESSION["username"])) {
            $user = $_SERVER["REMOTE_ADDR"];
        } else {
            $user = $_SESSION["username"];
        }

        self::write("[$prefix] [$user] $message");
    }


    /**
    * @author Axel Martin
    * @brief écrit un message de niveau debug
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    public static function debug($message) {
        self::write_standardized_log("dbug", $message);
    }


    /**
    * @author Axel Martin
    * @brief écrit un message de niveau info
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    public static function info($message) {
        self::write_standardized_log("info", $message);
    }


    /**
    * @author Axel Martin
    * @brief écrit un message de niveau warning
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    public static function warning($message) {
        self::write_standardized_log("warn", $message);
    }


    /**
    * @author Axel Martin
    * @brief écrit un message de niveau error
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    public static function error($message) {
        self::write_standardized_log("err ", $message);
    }


    /**
    * @author Axel Martin
    * @brief écrit un message de niveau critical
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    public static function critical($message) {
        self::write_standardized_log("crit", $message);
    }


    /**
    * @author Axel Martin
    * @brief Ajout d'un code d'erreur au programme
    * @version 0.1
    * @date 2014-11-10
    *
    * @param code identifiant du code
    * @param message message correspondant
    */
    public static function add_failure_code($code, $message) {
        if (in_array($code, array_keys(self::$_failure_code))) {
            self::exit_failure("Le code `$code` existe déjà.");
        }

        self::$_failure_code[$code] = $message;
    }


    /**
    * @author Axel Martin
    * @brief Ecriture d'un message de type failure (à partir d'un code ou d'un
    * message simple)
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    private static function write_failure_message($message) {
        if (!(constant("DEFAULT_FAILURE_CODE_LOG_LEVEL") >= Config::get("logger_level"))) {
            return;
        }

        if (!isset($_SESSION["username"])) {
            $user = $_SERVER["REMOTE_ADDR"];
        } else {
            $user = $_SESSION["username"];
        }

        self::write("[FAIL] [$user] $message");
    }


    /**
    * @author Axel Martin
    * @brief Ecriture d'un message de type failure, puis arrêt du programme
    * @version 0.1
    * @date 2014-11-10
    *
    * @param message message à écrire
    */
    public static function exit_failure($message) {
        self::write_failure_message($message);
        exit;
    }


    /**
    * @author Axel Martin
    * @brief Ecriture d'un message issue d'un code de type failure, puis arrêt
    * du programme
    * @version 0.1
    * @date 2014-11-10
    *
    * @param code code du mesage à écrire
    */
    public static function exit_failure_code($code, $exit_on_failure=true) {
        if (!array_key_exists($code, self::$_failure_code)) {
            self::write_failure_message("Invalid failure code message : $code");
        } else {
            $message = self::$_failure_code[$code];
            self::write_failure_message("[$code] $message");
        }

        if ($exit_on_failure) {
            exit;
        }
    }


    public static function failure_code($code) {
        self::exit_failure_code($code, false);
    }
}
