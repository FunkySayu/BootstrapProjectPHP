<?php
/**
* @file dba.class.php
* @author Axel Martin <axel.martin@eisti.fr>
* @brief Définition de la classe de gestion de la bdd
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


Logger::add_failure_code("DBA_CONNECTION_FAILURE", "Failed to get the database handle.");


/**
* @author Axel Martin
* @brief Interface de gestion de la base de donnée.
* @version 0.1
* @date 2014-11-10
*
*/
class DBA {

    // Utile pour d'éventuels débugs
    public static $stats = array("query" => 0);
    private static $_sql;
    private static $_error;


    /**
    * @author Axel Martin
    * @brief Réalisation d'une query vers la base de donnée
    * @version 0.1
    * @date 2014-11-10
    *
    * @param sql chaine SQL de requête BDD
    * @param params paramètres de la chaine SQL
    */
    public static function query($sql, $params = array()) {
        Logger::debug("Query: " . $sql . ' ' . json_encode($params));

        $tries = 0;
        do {
            $stmt = self::_query($sql, $params);
        } while (!$stmt && $tries++ < 3);

        return $stmt;
    }


    private static function _query($sql, $params) {
        $dbh = self::dbh();

        if (!$dbh) {
            Logger::exit_failure_code("DBA_CONNECTION_FAILURE", false);
            return false;
        }

        if ($params) {
            $stmt = $dbh->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = $dbh->query($sql);
        }

        self::$_sql = $sql;
        self::$stats['query']++;

        if (!$stmt) {
            self::$_error = json_encode($dbh->errorInfo());
            Logger::error("DBA: query error : " . self::$_error);
            self::disconnect();
        } else if ($stmt->errorCode() && $stmt->errorCode() != '00000') {
            self::$_error = json_encode($stmt->errorInfo());
            Logger::error("DBA: query error : " . self::$_error);
            self::finish($stmt);
            self::disconnect();
            return false;
        }

        return $stmt;
    }


    public static function get_last_id() {
        $dbh = self::dbh();
        try {
            return $dbh->lastInsertId();
        } catch (PDOException $e) {
            Logger::warning("DBA Exception (LastInsertId) : " . $e->getMessage());
            return -1;
        }
    }


    public static function fetch_assoc($resource, $finish = true) {
        if (!$resource) {
            return array();
        }

        $result = $resource->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            if ($finish) {
                self::finish($resource);
            }
            return array();
        }

        return $result;
    }


    public static function fetch_row($resource, $finish = true) {
        if (!$resource) {
            return array();
        }

        $result = $resource->fetch(PDO::FETCH_NUM);

        if (!$result) {
            if ($finish) {
                self::finish($resource);
            }
            return array();
        }

        return $result;
    }


    public static function num_rows($resource) {
        if ($resource) {
            $result = $resource->rowCount();
            if ($result) {
                return $result;
            }
        }

        return 0;
    }


    public static function finish($resource) {
        if ($resource) {
            $resource->closeCursor();
        }
    }


    private static function _connect() {
        $username = Config::get("database_username");
        $hostname = Config::get("database_hostname");
        $password = Config::get("database_password");
        $port     = Config::get("database_port");

        if (strpos($hostname, "/") === 0) {
            $dsn = "mysql:unix_socket=" . $hostname;
        } else {
            $dsn = "mysql:host=" . $hostname ?: "localhost";
        }

        if ($port) {
            $dsn .= ";port=" .intval($port);
        }

        try {
            Logger::debug("DBA: Database connection...");
            $dbh = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            self::$_error = $e->getMessage();
            Logger::error("DBA Connection failure : " . $e->getMessage());
            return null;
        }

        return $dbh;
    }


    private static function _setup_dbh($dbh, $database) {
        if (!$dbh) {
            return false;
        }

        $charset = Config::get("site_charset");

        if ($dbh->exec("SET NAMES " . $charset) === false) {
            Logger::error("DBA: Unable to set connnection charset to " . $charset);
        }

        if ($dbh->exec("USE `" . $database . "`") === false) {
            self::$_error = json_encode($dbh->errorInfo());
            Logger::error("DBA: Unable to select database " . $database . ": " . self::$_error);
        }

        if (Config::get("database_sql_profiling")) {
            $dbh->exec("SET profiling=1");
            $dbh->exec("SET profiling_history_size=50");
            $dbh->exec("SET query_cache_type=0");
        }
    }


    public static function dbh($database='') {
        if (!$database) {
            $database = Config::get("database_name");
        }

        $handle = "dbh_" . $database;

        if (!is_object(Config::get($handle))) {
            $dbh = self::_connect();
            self::_setup_dbh($dbh, $database);
            Config::set($handle, $dbh, true);
            return $dbh;
        } else {
            return Config::get($handle);
        }
    }


    public static function disconnect($database = "") {
        if (!$database) {
            $database = Config::get("database_name");
        }

        $handle = "dbh_" . $database;

        Logger::debug("DBA: Database disconnection.");
        Config::set($handle, null, true);

        return true;
    }


    public static function error() {
        return self::$_error;
    }
}
